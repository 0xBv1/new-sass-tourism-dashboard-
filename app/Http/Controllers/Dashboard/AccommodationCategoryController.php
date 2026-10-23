<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\AccommodationCategoryDataTable;
use App\Events\ResourceCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AccommodationCategoryRequest;
use App\Models\AccommodationCategory;

class AccommodationCategoryController extends Controller
{
    public function index(AccommodationCategoryDataTable $dataTable)
    {
        return $dataTable->render('dashboard.accommodation-categories.index');
    }

    public function create()
    {
        return view('dashboard.accommodation-categories.create');
    }

    public function store(AccommodationCategoryRequest $request)
    {
        $category = AccommodationCategory::create($request->getSanitized());
        session()->flash('message', 'Accommodation Category Created Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($category);
        return redirect()->route('dashboard.accommodation-categories.edit', $category);
    }

    public function edit(AccommodationCategory $accommodationCategory)
    {
        return view('dashboard.accommodation-categories.edit', compact('accommodationCategory'));
    }

    public function update(AccommodationCategoryRequest $request, AccommodationCategory $accommodationCategory)
    {
        $accommodationCategory->update($request->getSanitized());
        session()->flash('message', 'Accommodation Category Updated Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($accommodationCategory);
        return back();
    }

    public function destroy(AccommodationCategory $accommodationCategory)
    {
        $accommodationCategory->delete();
        return response()->json([
            'message' => 'Accommodation Category Deleted Successfully!'
        ]);
    }
}
