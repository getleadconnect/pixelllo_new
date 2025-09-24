<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource with datatable pagination.
     */
    public function index(Request $request)
    {
        $query = Category::with(['parent'])
            ->withCount('auctions');

        // Handle search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        // Handle sorting
        $sortColumn = $request->get('sort_column', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        if ($sortColumn == 'auctions_count') {
            $query->orderBy('auctions_count', $sortDirection);
        } else {
            $query->orderBy($sortColumn, $sortDirection);
        }

        // Get paginated results with proper per_page handling
        $perPage = $request->get('per_page', 10);
        $categories = $query->paginate($perPage)->appends($request->except('page'));

        if ($request->ajax()) {
            return response()->json($categories);
        }

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Display the category management page with create form.
     */
    public function manage()
    {
        $categories = Category::with(['parent'])
            ->withCount('auctions')
            ->orderBy('name', 'asc')
            ->get();

        $parentCategories = Category::whereNull('parent_id')
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.categories.manage', compact('categories', 'parentCategories'));
    }

    /**
     * Store a newly created resource in storage (API).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $slug = Str::slug($request->name);

        // Check if slug already exists
        $count = Category::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'slug' => $slug
        ]);

        return response()->json($category, 201);
    }

    /**
     * Store a newly created resource in storage (Web UI).
     */
    public function storeWeb(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured' => 'nullable|boolean'
        ]);

        $slug = Str::slug($request->name);

        // Check if slug already exists
        $count = Category::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'slug' => $slug,
            'featured' => $request->has('featured') ? true : false
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('categories', 'public');
            $data['image'] = $imagePath;
        }

        $category = Category::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
                'category' => $category
            ]);
        }

        return redirect()->route('admin.categories')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with(['parent', 'children', 'auctions'])->findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage (API).
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        // Make sure parent_id isn't itself to prevent circular references
        if ($request->parent_id == $id) {
            return response()->json(['message' => 'A category cannot be its own parent'], 422);
        }

        // Check if new parent is one of the category's descendants to prevent circular references
        if ($request->has('parent_id') && $request->parent_id !== null) {
            // Get all descendants
            $descendants = $this->getAllDescendants($category);
            if (in_array($request->parent_id, $descendants->pluck('id')->toArray())) {
                return response()->json(['message' => 'Cannot set a descendant as parent'], 422);
            }
        }

        // Update slug if name changes
        if ($request->has('name') && $request->name !== $category->name) {
            $slug = Str::slug($request->name);

            // Check if slug already exists
            $count = Category::where('slug', 'like', $slug . '%')
                ->where('id', '!=', $id)
                ->count();

            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }

            $category->slug = $slug;
        }

        // Update other attributes
        $category->fill($request->only(['name', 'description', 'parent_id']));
        $category->save();

        return response()->json($category);
    }

    /**
     * Update the specified resource in storage (Web UI).
     */
    public function updateWeb(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'featured' => 'nullable|boolean'
        ]);

        // Make sure parent_id isn't itself to prevent circular references
        if ($request->parent_id == $id) {
            return back()->withErrors(['parent_id' => 'A category cannot be its own parent'])->withInput();
        }

        // Check if new parent is one of the category's descendants to prevent circular references
        if ($request->has('parent_id') && $request->parent_id !== null) {
            // Get all descendants
            $descendants = $this->getAllDescendants($category);
            if (in_array($request->parent_id, $descendants->pluck('id')->toArray())) {
                return back()->withErrors(['parent_id' => 'Cannot set a descendant as parent'])->withInput();
            }
        }

        // Update slug if name changes
        if ($request->name !== $category->name) {
            $slug = Str::slug($request->name);

            // Check if slug already exists
            $count = Category::where('slug', 'like', $slug . '%')
                ->where('id', '!=', $id)
                ->count();

            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }

            $category->slug = $slug;
        }

        // Update other attributes
        $category->fill($request->only(['name', 'description', 'parent_id']));
        $category->featured = $request->has('featured') ? true : false;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $image = $request->file('image');
            $imagePath = $image->store('categories', 'public');
            $category->image = $imagePath;
        }

        $category->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully.',
                'category' => $category
            ]);
        }

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully.');
    }

    /**
     * Get all descendants of a category
     *
     * @param Category $category
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAllDescendants(Category $category)
    {
        $descendants = collect();

        $children = $category->children;
        $descendants = $descendants->merge($children);

        foreach ($children as $child) {
            $descendants = $descendants->merge($this->getAllDescendants($child));
        }

        return $descendants;
    }

    /**
     * Reassign all auctions from one category to another
     */
    public function reassignAuctions(Request $request, string $id)
    {
        $request->validate([
            'target_category_id' => 'required|exists:categories,id'
        ]);

        $sourceCategory = Category::findOrFail($id);
        $targetCategory = Category::findOrFail($request->target_category_id);

        // Make sure we're not trying to reassign to the same category
        if ($id === $request->target_category_id) {
            return back()->withErrors([
                'target_category_id' => 'Cannot reassign auctions to the same category'
            ])->withInput();
        }

        // Count auctions before reassigning
        $auctionCount = $sourceCategory->auctions()->count();

        if ($auctionCount === 0) {
            return back()->with('error', 'No auctions to reassign.');
        }

        // Update all auctions to the new category
        $sourceCategory->auctions()->update(['category_id' => $targetCategory->id]);

        return back()->with('success', "Successfully reassigned {$auctionCount} auction(s) from \"{$sourceCategory->name}\" to \"{$targetCategory->name}\".");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $category = Category::findOrFail($id);

        // Check if the category has children
        if ($category->children()->count() > 0) {
            $errorMessage = 'Cannot delete a category with subcategories. Please delete or reassign subcategories first.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $errorMessage], 422);
            }

            return redirect()->route('admin.categories')->with('error', $errorMessage);
        }

        // Check if the category has auctions
        if ($category->auctions()->count() > 0) {
            $errorMessage = 'Cannot delete a category with auctions. Please delete or reassign auctions first.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $errorMessage], 422);
            }

            return redirect()->route('admin.categories')->with('error', $errorMessage);
        }

        // Delete image if exists
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully'
            ]);
        }

        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully');
    }
}
