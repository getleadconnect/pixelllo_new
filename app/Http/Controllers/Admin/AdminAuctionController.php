<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminAuctionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware is applied in routes/web.php instead
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auction::with('category');

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('featured')) {
            $query->where('featured', $request->featured === 'true');
        }

        // Apply search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSortFields = ['title', 'startTime', 'endTime', 'currentPrice', 'created_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $auctions = $query->paginate($perPage);

        // Update auction status based on endTime
        $now = now();
        foreach ($auctions as $auction) {
            if ($auction->endTime && $auction->endTime < $now) {
                $auction->status = 'ended';
            }
        }

        // Return view instead of JSON
        return view('admin.auctions.index', compact('auctions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'startingPrice' => 'required|numeric|min:0',
            'bidIncrement' => 'required|numeric|min:0.01',
            'retailPrice' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'startTime' => 'required|date|after:now',
            'endTime' => 'required|date|after:startTime',
            'extensionTime' => 'required|integer|min:0',
            'featured' => 'boolean',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
        ]);

        // Handle image uploads
        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('auctions', 'public');
                $imagesPaths[] = $path;
            }
        }

        $auction = Auction::create([
            'title' => $request->title,
            'description' => $request->description,
            'startingPrice' => $request->startingPrice,
            'currentPrice' => $request->startingPrice,
            'bidIncrement' => $request->bidIncrement,
            'retailPrice' => $request->retailPrice,
            'category_id' => $request->category_id,
            'status' => 'upcoming',
            'startTime' => $request->startTime,
            'endTime' => $request->endTime,
            'extensionTime' => $request->extensionTime,
            'featured' => $request->featured ?? false,
            'images' => $imagesPaths,
        ]);

        return redirect()->route('admin.auctions.show', $auction->id)->with('success', 'Auction created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $auction = Auction::with(['category', 'bids.user'])->findOrFail($id);
        return view('admin.auctions.show', compact('auction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $auction = Auction::with('category')->findOrFail($id);
        $categories = Category::all();
        return view('admin.auctions.edit', compact('auction', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $auction = Auction::findOrFail($id);

        // We only allow certain updates based on auction status
        if ($auction->status === 'active') {
            // When auction is active, only allow updating end time and featured status
            $request->validate([
                'endTime' => 'required|date',
                'featured' => 'nullable|boolean',
            ]);

            $updateData = [
                'endTime' => $request->endTime,
                'featured' => $request->has('featured') ? true : false,
            ];

            $auction->update($updateData);
        } else if ($auction->status === 'upcoming') {
            // For upcoming auctions, allow updating almost everything
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'startingPrice' => 'required|numeric|min:0',
                'bidIncrement' => 'required|numeric|min:0.01',
                'retailPrice' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'startTime' => 'required|date',
                'endTime' => 'required|date|after:startTime',
                'extensionTime' => 'required|integer|min:0',
                'featured' => 'nullable|boolean',
            ]);

            // Prepare update data
            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'startingPrice' => $request->startingPrice,
                'bidIncrement' => $request->bidIncrement,
                'retailPrice' => $request->retailPrice,
                'category_id' => $request->category_id,
                'startTime' => $request->startTime,
                'endTime' => $request->endTime,
                'extensionTime' => $request->extensionTime,
                'featured' => $request->has('featured') ? true : false,
            ];

            // If changing starting price, update current price as well
            if ($request->has('startingPrice') && $auction->currentPrice == $auction->startingPrice) {
                $updateData['currentPrice'] = $request->startingPrice;
            }

            $auction->update($updateData);
        } else if (in_array($auction->status, ['ended', 'cancelled'])) {
            // For ended or cancelled auctions, only allow changing featured status
            $request->validate([
                'featured' => 'nullable|boolean',
            ]);

            $updateData = [];
            $updateData['featured'] = $request->has('featured') ? true : false;
            $auction->update($updateData);
        }

        return redirect()->route('admin.auctions.show', $auction->id)->with('success', 'Auction updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $auction = Auction::findOrFail($id);

        // Check if auction can be deleted
        if ($auction->status === 'active') {
            return redirect()->back()->with('error', 'Cannot delete an active auction. Cancel it first.');
        }

        // Check if it has bids
        if ($auction->bids()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete an auction with bids.');
        }

        // Delete associated images
        if (!empty($auction->images)) {
            foreach ($auction->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $auction->delete();

        return redirect()->route('admin.auctions')->with('success', 'Auction deleted successfully');
    }

    /**
     * Update the status of an auction
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, string $id)
    {
        $auction = Auction::findOrFail($id);

        $request->validate([
            'status' => 'required|in:upcoming,active,ended,cancelled'
        ]);

        $newStatus = $request->status;
        $currentStatus = $auction->status;

        // Check valid status transitions
        $validTransitions = [
            'upcoming' => ['active', 'cancelled'],
            'active' => ['ended', 'cancelled'],
            'ended' => [], // No transitions allowed
            'cancelled' => ['upcoming'] // Can reopen cancelled auctions only if they were upcoming
        ];

        if (!in_array($newStatus, $validTransitions[$currentStatus])) {
            return redirect()->back()->with('error', "Cannot change auction status from {$currentStatus} to {$newStatus}");
        }

        // Special case for activating an auction: check if startTime is in the past
        if ($newStatus === 'active' && $auction->startTime > now()) {
            $auction->startTime = now();
        }

        // Special case for ending an auction: set the endTime to now
        if ($newStatus === 'ended' && $auction->endTime > now()) {
            $auction->endTime = now();
        }

        $auction->status = $newStatus;
        $auction->save();

        return redirect()->route('admin.auctions.show', $auction->id)->with('success', "Auction status changed to {$newStatus}");
    }

    /**
     * Upload an image for an auction
     *
     * @param Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadImage(Request $request, string $id)
    {
        $auction = Auction::findOrFail($id);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // max 5MB
        ]);

        $path = $request->file('image')->store('auctions', 'public');

        // Make sure images is an array
        $images = is_array($auction->images) ? $auction->images : [];
        $images[] = $path;

        $auction->images = $images;
        $auction->save();

        return redirect()->route('admin.auctions.edit', $auction->id)->with('success', 'Image uploaded successfully');
    }

    /**
     * Delete an image from an auction
     *
     * @param string $id
     * @param string $imageIndex
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteImage(string $id, string $imageIndex)
    {
        $auction = Auction::findOrFail($id);

        // Make sure images is an array
        $images = is_array($auction->images) ? $auction->images : [];

        // Convert to integer for array access
        $imageIndex = (int) $imageIndex;

        if (!isset($images[$imageIndex])) {
            return redirect()->back()->with('error', 'Image not found');
        }

        $path = $images[$imageIndex];

        // Remove image from storage
        Storage::disk('public')->delete($path);

        // Remove image from auction
        unset($images[$imageIndex]);
        $auction->images = array_values($images); // Reindex array
        $auction->save();

        return redirect()->route('admin.auctions.edit', $auction->id)->with('success', 'Image deleted successfully');
    }
}
