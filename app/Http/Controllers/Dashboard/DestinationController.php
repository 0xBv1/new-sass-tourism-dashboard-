<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\DestinationDataTable;
use App\Events\ResourceCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\DestinationRequest;
use App\Models\Destination;

class DestinationController extends Controller
{

    public function index(DestinationDataTable $dataTable)
    {
        return $dataTable->render('dashboard.destinations.index');
    }


    public function create()
    {
        $parent_destinations = Destination::whereNull('parent_id')->get();
        return view('dashboard.destinations.create', compact('parent_destinations'));
    }


    public function store(DestinationRequest $request)
    {
        $destination = Destination::create($request->getSanitized());
        $destination->seo()->create($request->get('seo'));
        session()->flash('message', 'Destination Created Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($destination);
        return redirect()->route('dashboard.destinations.edit', $destination);
    }


    public function show(Destination $destination)
    {
        //
    }


    public function edit(Destination $destination)
    {
        $parent_destinations = Destination::whereNull('parent_id')
            ->where('id', '!=', $destination->id)->get();
        return view('dashboard.destinations.edit', compact('destination', 'parent_destinations'));
    }


    public function update(DestinationRequest $request, Destination $destination)
    {
        $destination->update($request->getSanitized());
        $destination->seo ?
            $destination->seo->update($request->get('seo')) :
            $destination->seo()->create($request->get('seo'));
        session()->flash('message', 'Destination Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(Destination $destination)
    {
        $destination->delete();
        return response()->json([
            'message' => 'Destination Deleted Successfully!'
        ]);
    }
}
