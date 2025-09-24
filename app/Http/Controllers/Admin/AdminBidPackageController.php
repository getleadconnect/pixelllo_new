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
        $bidPackages = BidPackage::orderBy('bidAmount', 'asc')->get();
        return response()->json($bidPackages);
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

        $bidPackage = BidPackage::create([
            'name' => $request->name,
            'bidAmount' => $request->bidAmount,
            'price' => $request->price,
            'description' => $request->description,
            'isActive' => $request->isActive ?? true
        ]);

        return response()->json($bidPackage, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bidPackage = BidPackage::findOrFail($id);
        return response()->json($bidPackage);
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

        $bidPackage->update($request->all());

        return response()->json($bidPackage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bidPackage = BidPackage::findOrFail($id);

        // Instead of deleting, we often just deactivate bid packages
        // to maintain history for users who purchased them
        $bidPackage->isActive = false;
        $bidPackage->save();

        return response()->json(['message' => 'Bid package deactivated successfully']);
    }
}
