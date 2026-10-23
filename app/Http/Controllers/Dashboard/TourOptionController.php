<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\TourOptionDataTable;
use App\Events\ResourceCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\TourOptionRequest;
use App\Models\Tour;
use App\Models\TourOption;

class TourOptionController extends Controller
{

    public function index(TourOptionDataTable $dataTable)
    {
        return $dataTable->render('dashboard.tour-options.index');
    }


    public function create()
    {
        return view('dashboard.tour-options.create');
    }


    public function store(TourOptionRequest $request)
    {
        $option = TourOption::create($request->getSanitized());
        session()->flash('message', 'Tour Option Created Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($option);
        return redirect()->route('dashboard.tour-options.edit', $option);
    }


    public function show(TourOption $tourOption)
    {
    }


    public function edit(TourOption $tourOption)
    {
        return view('dashboard.tour-options.edit', compact('tourOption'));
    }


    public function update(TourOptionRequest $request, TourOption $tourOption)
    {
        $tourOption->update($request->getSanitized());
        session()->flash('message', 'Tour Option Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(TourOption $tourOption)
    {
        $tourOption->delete();
        return response()->json([
            'message' => 'Tour Option Deleted Successfully!'
        ]);
    }
}
