<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\ResourceCreatedEvent;
use App\Models\CustomizedTripCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CustomizedTripCategoryRequest;
use App\DataTables\CustomizedTripCategoryDataTable;

class CustomizedTripCategoryController extends Controller
{

    public function index(CustomizedTripCategoryDataTable $dataTable)
    {
        return $dataTable->render('dashboard.customized-trip-categories.index');
    }


    public function create()
    {
        return view('dashboard.customized-trip-categories.create');
    }


    public function store(CustomizedTripCategoryRequest $request)
    {
        $customizedTripCategory = CustomizedTripCategory::create($request->getSanitized());
        session()->flash('message', 'Customized Trip Category Created Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($customizedTripCategory);
        return redirect()->route('dashboard.customized-trip-categories.edit', $customizedTripCategory);
    }


    public function show(CustomizedTripCategory $customizedTripCategory)
    {
        //
    }


    public function edit(CustomizedTripCategory $customizedTripCategory)
    {
        return view('dashboard.customized-trip-categories.edit', compact('customizedTripCategory'));
    }


    public function update(CustomizedTripCategoryRequest $request, CustomizedTripCategory $customizedTripCategory)
    {
        $customizedTripCategory->update($request->getSanitized());
        session()->flash('message', 'Customized Trip Category Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(CustomizedTripCategory $customizedTripCategory)
    {
        $customizedTripCategory->delete();
        return response()->json([
            'message' => 'Customized Trip Category Deleted Successfully!'
        ]);
    }
}
