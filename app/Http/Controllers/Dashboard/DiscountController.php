<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\DiscountDataTable;
use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Tour;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(DiscountDataTable $dataTable)
    {
        return $dataTable->render('dashboard.discounts.index');
    }

    public function create()
    {
        $tours = Tour::enabled()->get();
        $types = Discount::getTypes();
        return view('dashboard.discounts.create', compact('tours', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'priority' => 'nullable|integer|min:0',
            'tours' => 'nullable|array',
            'tours.*' => 'exists:tours,id',
        ]);

        $discount = Discount::create($request->except('tours'));

        if ($request->has('tours')) {
            $discount->tours()->sync($request->tours);
        }

        return redirect()->route('dashboard.discounts.index')
            ->with('success', 'Discount created successfully.');
    }

    public function edit(Discount $discount)
    {
        $tours = Tour::enabled()->get();
        $types = Discount::getTypes();
        $selectedTours = $discount->tours->pluck('id')->toArray();
        return view('dashboard.discounts.edit', compact('discount', 'tours', 'types', 'selectedTours'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'priority' => 'nullable|integer|min:0',
            'tours' => 'nullable|array',
            'tours.*' => 'exists:tours,id',
        ]);

        $discount->update($request->except('tours'));
        $discount->tours()->sync($request->tours ?? []);

        return redirect()->route('dashboard.discounts.index')
            ->with('success', 'Discount updated successfully.');
    }

    public function destroy(Discount $discount)
    {
        $discount->tours()->detach();
        $discount->delete();

        return back()->with('success', 'Discount deleted successfully.');
    }
}

