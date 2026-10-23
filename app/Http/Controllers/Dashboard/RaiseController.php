<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\RaiseDataTable;
use App\Http\Controllers\Controller;
use App\Models\Raise;
use App\Models\Tour;
use Illuminate\Http\Request;

class RaiseController extends Controller
{
    public function index(RaiseDataTable $dataTable)
    {
        return $dataTable->render('dashboard.raises.index');
    }

    public function create()
    {
        $tours = Tour::enabled()->get();
        $types = Raise::getTypes();
        return view('dashboard.raises.create', compact('tours', 'types'));
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

        $raise = Raise::create($request->except('tours'));

        if ($request->has('tours')) {
            $raise->tours()->sync($request->tours);
        }

        return redirect()->route('dashboard.raises.index')
            ->with('success', 'Price increase created successfully.');
    }

    public function edit(Raise $raise)
    {
        $tours = Tour::enabled()->get();
        $types = Raise::getTypes();
        $selectedTours = $raise->tours->pluck('id')->toArray();
        return view('dashboard.raises.edit', compact('raise', 'tours', 'types', 'selectedTours'));
    }

    public function update(Request $request, Raise $raise)
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

        $raise->update($request->except('tours'));
        $raise->tours()->sync($request->tours ?? []);

        return redirect()->route('dashboard.raises.index')
            ->with('success', 'Price increase updated successfully.');
    }

    public function destroy(Raise $raise)
    {
        $raise->tours()->detach();
        $raise->delete();

        return back()->with('success', 'Price increase deleted successfully.');
    }
}

