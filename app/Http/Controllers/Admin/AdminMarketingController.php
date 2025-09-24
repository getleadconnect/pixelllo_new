<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdminMarketingController extends Controller
{
    /**
     * Display the marketing dashboard
     */
    public function index()
    {
        // Get current slider images
        $sliderImages = $this->getSliderImages();
        
        return view('admin.marketing.index', compact('sliderImages'));
    }

    /**
     * Display slider management page
     */
    public function slider()
    {
        $sliderImages = $this->getSliderImages();
        
        return view('admin.marketing.slider', compact('sliderImages'));
    }

    /**
     * Upload a new slider image
     */
    public function uploadSliderImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url',
        ]);

        // Store the image
        $path = $request->file('image')->store('slider', 'public');

        // Get current slider data
        $sliderData = $this->getSliderData();

        // Add new slide
        $newSlide = [
            'image' => $path,
            'title' => $request->title ?? '',
            'subtitle' => $request->subtitle ?? '',
            'button_text' => $request->button_text ?? '',
            'button_link' => $request->button_link ?? '',
            'active' => true,
            'order' => count($sliderData) + 1,
            'created_at' => now()->toISOString()
        ];

        $sliderData[] = $newSlide;

        // Save to config file
        $this->saveSliderData($sliderData);

        return redirect()->route('admin.marketing.slider')->with('success', 'Slider image uploaded successfully');
    }

    /**
     * Update slider image details
     */
    public function updateSliderImage(Request $request, $index)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url',
            'active' => 'boolean',
        ]);

        $sliderData = $this->getSliderData();

        if (!isset($sliderData[$index])) {
            return redirect()->back()->with('error', 'Slider image not found');
        }

        // Update slide data
        $sliderData[$index]['title'] = $request->title ?? '';
        $sliderData[$index]['subtitle'] = $request->subtitle ?? '';
        $sliderData[$index]['button_text'] = $request->button_text ?? '';
        $sliderData[$index]['button_link'] = $request->button_link ?? '';
        $sliderData[$index]['active'] = $request->has('active');

        $this->saveSliderData($sliderData);

        return redirect()->route('admin.marketing.slider')->with('success', 'Slider image updated successfully');
    }

    /**
     * Delete a slider image
     */
    public function deleteSliderImage($index)
    {
        $sliderData = $this->getSliderData();

        if (!isset($sliderData[$index])) {
            return redirect()->back()->with('error', 'Slider image not found');
        }

        // Delete the image file
        $imagePath = $sliderData[$index]['image'];
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        // Remove from array
        unset($sliderData[$index]);
        $sliderData = array_values($sliderData); // Reindex array

        $this->saveSliderData($sliderData);

        return redirect()->route('admin.marketing.slider')->with('success', 'Slider image deleted successfully');
    }

    /**
     * Reorder slider images
     */
    public function reorderSliderImages(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|min:0',
        ]);

        $sliderData = $this->getSliderData();
        $newOrder = $request->order;

        // Reorder the slides
        $reorderedData = [];
        foreach ($newOrder as $oldIndex) {
            if (isset($sliderData[$oldIndex])) {
                $slide = $sliderData[$oldIndex];
                $slide['order'] = count($reorderedData) + 1;
                $reorderedData[] = $slide;
            }
        }

        $this->saveSliderData($reorderedData);

        return response()->json(['success' => true, 'message' => 'Slider order updated successfully']);
    }

    /**
     * Get slider images for display
     */
    private function getSliderImages()
    {
        $sliderData = $this->getSliderData();
        
        return array_map(function($slide, $index) {
            return [
                'index' => $index,
                'image' => $slide['image'],
                'url' => asset('storage/' . $slide['image']),
                'title' => $slide['title'] ?? '',
                'subtitle' => $slide['subtitle'] ?? '',
                'button_text' => $slide['button_text'] ?? '',
                'button_link' => $slide['button_link'] ?? '',
                'active' => $slide['active'] ?? true,
                'order' => $slide['order'] ?? $index + 1,
            ];
        }, $sliderData, array_keys($sliderData));
    }

    /**
     * Get slider data from storage
     */
    private function getSliderData()
    {
        $configPath = storage_path('app/slider-config.json');
        
        if (File::exists($configPath)) {
            $content = File::get($configPath);
            return json_decode($content, true) ?? [];
        }

        return [];
    }

    /**
     * Save slider data to storage
     */
    private function saveSliderData($data)
    {
        $configPath = storage_path('app/slider-config.json');
        File::put($configPath, json_encode($data, JSON_PRETTY_PRINT));
    }
}