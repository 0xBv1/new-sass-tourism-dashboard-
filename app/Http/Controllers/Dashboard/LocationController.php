<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\ResourceCreatedEvent;
use App\Models\Location;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\LocationRequest;
use App\DataTables\LocationDataTable;

class LocationController extends Controller
{

    public function index(LocationDataTable $dataTable)
    {
        return $dataTable->render('dashboard.locations.index');
    }


    public function create()
    {
        return view('dashboard.locations.create');
    }


    public function store(LocationRequest $request)
    {
        $location = Location::create($request->getSanitized());
        session()->flash('message', 'Location Created Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($location);
        return redirect()->route('dashboard.locations.edit', $location);
    }


    public function show(Location $location)
    {
        //
    }


    public function edit(Location $location)
    {
        return view('dashboard.locations.edit', compact('location'));
    }


    public function update(LocationRequest $request, Location $location)
    {
        $location->update($request->getSanitized());
        session()->flash('message', 'Location Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(Location $location)
    {
        $location->delete();
        return response()->json([
            'message' => 'Location Deleted Successfully!'
        ]);
    }
}
