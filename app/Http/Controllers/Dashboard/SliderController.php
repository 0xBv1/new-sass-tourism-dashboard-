<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\SliderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Slide;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index(SliderDataTable $dataTable)
    {
        return $dataTable->render('dashboard.sliders.index');
    }

    public function create()
    {
        $commonKeys = Slider::getCommonKeys();
        $buttonStyles = Slide::getButtonStyles();
        return view('dashboard.sliders.create', compact('commonKeys', 'buttonStyles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:sliders,key',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'slides' => 'nullable|array',
            'slides.*.title' => 'nullable|string|max:255',
            'slides.*.subtitle' => 'nullable|string|max:255',
            'slides.*.description' => 'nullable|string',
            'slides.*.image' => 'required|image|max:5120',
            'slides.*.mobile_image' => 'nullable|image|max:2048',
            'slides.*.link' => 'nullable|string|max:255',
            'slides.*.button_text' => 'nullable|string|max:50',
            'slides.*.button_style' => 'nullable|in:primary,secondary,outline',
            'slides.*.is_active' => 'boolean',
        ]);

        $slider = Slider::create($request->only(['name', 'key', 'description', 'is_active']));

        if ($request->has('slides')) {
            foreach ($request->slides as $index => $slideData) {
                $imagePath = $slideData['image']->store('sliders', 'public');
                $mobileImagePath = isset($slideData['mobile_image']) 
                    ? $slideData['mobile_image']->store('sliders', 'public') 
                    : null;

                $slider->slides()->create([
                    'title' => $slideData['title'] ?? null,
                    'subtitle' => $slideData['subtitle'] ?? null,
                    'description' => $slideData['description'] ?? null,
                    'image' => $imagePath,
                    'mobile_image' => $mobileImagePath,
                    'link' => $slideData['link'] ?? null,
                    'button_text' => $slideData['button_text'] ?? null,
                    'button_style' => $slideData['button_style'] ?? 'primary',
                    'order' => $index,
                    'is_active' => $slideData['is_active'] ?? true,
                ]);
            }
        }

        return redirect()->route('dashboard.sliders.index')
            ->with('success', 'Slider created successfully.');
    }

    public function edit(Slider $slider)
    {
        $slider->load('slides');
        $commonKeys = Slider::getCommonKeys();
        $buttonStyles = Slide::getButtonStyles();
        return view('dashboard.sliders.edit', compact('slider', 'commonKeys', 'buttonStyles'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:sliders,key,' . $slider->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $slider->update($request->only(['name', 'key', 'description', 'is_active']));

        return redirect()->route('dashboard.sliders.index')
            ->with('success', 'Slider updated successfully.');
    }

    public function destroy(Slider $slider)
    {
        // Delete slide images
        foreach ($slider->slides as $slide) {
            if ($slide->image) {
                Storage::disk('public')->delete($slide->image);
            }
            if ($slide->mobile_image) {
                Storage::disk('public')->delete($slide->mobile_image);
            }
        }

        $slider->delete();

        return back()->with('success', 'Slider deleted successfully.');
    }

    public function addSlide(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:5120',
            'mobile_image' => 'nullable|image|max:2048',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:50',
            'button_style' => 'nullable|in:primary,secondary,outline',
            'is_active' => 'boolean',
        ]);

        $imagePath = $request->file('image')->store('sliders', 'public');
        $mobileImagePath = $request->hasFile('mobile_image') 
            ? $request->file('mobile_image')->store('sliders', 'public') 
            : null;

        $maxOrder = $slider->slides()->max('order') ?? -1;

        $slider->slides()->create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'image' => $imagePath,
            'mobile_image' => $mobileImagePath,
            'link' => $request->link,
            'button_text' => $request->button_text,
            'button_style' => $request->button_style ?? 'primary',
            'order' => $maxOrder + 1,
            'is_active' => $request->is_active ?? true,
        ]);

        return back()->with('success', 'Slide added successfully.');
    }

    public function removeSlide(Slider $slider, Slide $slide)
    {
        if ($slide->slider_id !== $slider->id) {
            return back()->with('error', 'Slide does not belong to this slider.');
        }

        if ($slide->image) {
            Storage::disk('public')->delete($slide->image);
        }
        if ($slide->mobile_image) {
            Storage::disk('public')->delete($slide->mobile_image);
        }

        $slide->delete();

        return back()->with('success', 'Slide removed successfully.');
    }
}

