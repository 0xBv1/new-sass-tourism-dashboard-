<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Amenity;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\AmenityRequest;
use App\DataTables\AmenityDataTable;

class AmenityController extends Controller
{

    public function index(AmenityDataTable $dataTable)
    {
        return $dataTable->render('dashboard.amenities.index');
    }


    public function create()
    {
        return view('dashboard.amenities.create');
    }


    public function store(AmenityRequest $request)
    {
        $amenity = Amenity::create($request->getSanitized());
        session()->flash('message', 'Amenity Created Successfully!');
        session()->flash('type', 'success');
        return redirect()->route('dashboard.amenities.edit', $amenity);
    }


    public function show(Amenity $amenity)
    {
        //
    }


    public function edit(Amenity $amenity)
    {
        return view('dashboard.amenities.edit', compact('amenity'));
    }


    public function update(AmenityRequest $request, Amenity $amenity)
    {
        $amenity->update($request->getSanitized());
        session()->flash('message', 'Amenity Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(Amenity $amenity)
    {
        $amenity->delete();
        return response()->json([
            'message' => 'Amenity Deleted Successfully!'
        ]);
    }
}
