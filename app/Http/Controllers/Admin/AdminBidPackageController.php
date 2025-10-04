<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidPackage;
use Illuminate\Http\Request;

class AdminBidPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packages = BidPackage::orderBy('bidAmount', 'asc')->get();
        return view('admin.bid-packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new bid package.
     */
    public function create()
    {
        return view('admin.bid-packages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'bidAmount' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'isActive' => 'boolean'
        ]);

        BidPackage::create([
            'name' => $request->name,
            'bidAmount' => $request->bidAmount,
            'price' => $request->price,
            'description' => $request->description,
            'isActive' => $request->has('isActive') ? true : false
        ]);

        return redirect()->route('admin.bid-packages.index')
            ->with('success', 'Bid package created successfully.');
    }

    /**
     * Show the form for editing the specified bid package.
     */
    public function edit(string $id)
    {
        $package = BidPackage::findOrFail($id);
        return view('admin.bid-packages.edit', compact('package'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bidPackage = BidPackage::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'bidAmount' => 'sometimes|required|integer|min:1',
            'price' => 'sometimes|required|numeric|min:0.01',
            'description' => 'nullable|string',
            'isActive' => 'sometimes|boolean'
        ]);

        $bidPackage->update([
            'name' => $request->name,
            'bidAmount' => $request->bidAmount,
            'price' => $request->price,
            'description' => $request->description,
            'isActive' => $request->has('isActive') ? true : false
        ]);

        return redirect()->route('admin.bid-packages.index')
            ->with('success', 'Bid package updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bidPackage = BidPackage::findOrFail($id);
        $bidPackage->delete();

        return redirect()->route('admin.bid-packages.index')
            ->with('success', 'Bid package deleted successfully.');
    }
}
