<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourPackageController extends Controller
{
    public function index()
    {
        $packages = TourPackage::with(['items.tour'])
            ->orderBy('display_order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.tour-packages.index', compact('packages'));
    }

    public function create()
    {
        $tours = Tour::enabled()->orderBy('display_order')->get();
        return view('dashboard.tour-packages.create', compact('tours'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tour_packages,slug',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'package_price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'max_bookings' => 'nullable|integer|min:1',
            'enabled' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            'tours' => 'required|array|min:1',
            'tours.*.tour_id' => 'required|exists:tours,id',
            'tours.*.quantity' => 'nullable|integer|min:1',
            'tours.*.individual_price' => 'nullable|numeric|min:0',
        ]);

        if (!isset($validated['slug']) || !$validated['slug']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('tour-packages', 'public');
        }

        $tours = $validated['tours'];
        unset($validated['tours']);

        $package = TourPackage::create($validated);

        // Calculate original price if not provided
        if (!$package->original_price) {
            $originalPrice = 0;
            foreach ($tours as $tourData) {
                $tour = Tour::find($tourData['tour_id']);
                $qty = $tourData['quantity'] ?? 1;
                $originalPrice += ($tourData['individual_price'] ?? $tour->adult_price) * $qty;
            }
            $package->update(['original_price' => $originalPrice]);
        }

        // Add tours to package
        foreach ($tours as $index => $tourData) {
            $package->items()->create([
                'tour_id' => $tourData['tour_id'],
                'quantity' => $tourData['quantity'] ?? 1,
                'individual_price' => $tourData['individual_price'] ?? null,
                'display_order' => $index,
            ]);
        }

        return redirect()->route('dashboard.tour-packages.index')
            ->with('success', 'Package created successfully.');
    }

    public function edit(TourPackage $tourPackage)
    {
        $tourPackage->load('items.tour');
        $tours = Tour::enabled()->orderBy('display_order')->get();
        
        return view('dashboard.tour-packages.edit', compact('tourPackage', 'tours'));
    }

    public function update(Request $request, TourPackage $tourPackage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tour_packages,slug,' . $tourPackage->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'package_price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'max_bookings' => 'nullable|integer|min:1',
            'enabled' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            'tours' => 'required|array|min:1',
            'tours.*.tour_id' => 'required|exists:tours,id',
            'tours.*.quantity' => 'nullable|integer|min:1',
            'tours.*.individual_price' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('tour-packages', 'public');
        }

        $tours = $validated['tours'];
        unset($validated['tours']);

        $tourPackage->update($validated);

        // Re-sync tours
        $tourPackage->items()->delete();
        
        $originalPrice = 0;
        foreach ($tours as $index => $tourData) {
            $tour = Tour::find($tourData['tour_id']);
            $qty = $tourData['quantity'] ?? 1;
            $price = $tourData['individual_price'] ?? $tour->adult_price;
            $originalPrice += $price * $qty;

            $tourPackage->items()->create([
                'tour_id' => $tourData['tour_id'],
                'quantity' => $qty,
                'individual_price' => $tourData['individual_price'] ?? null,
                'display_order' => $index,
            ]);
        }

        if (!$tourPackage->original_price) {
            $tourPackage->update(['original_price' => $originalPrice]);
        }

        return redirect()->route('dashboard.tour-packages.index')
            ->with('success', 'Package updated successfully.');
    }

    public function destroy(TourPackage $tourPackage)
    {
        $tourPackage->delete();
        return back()->with('success', 'Package deleted successfully.');
    }

    public function toggleEnabled(TourPackage $tourPackage)
    {
        $tourPackage->update(['enabled' => !$tourPackage->enabled]);
        return response()->json(['success' => true, 'enabled' => $tourPackage->enabled]);
    }
}

