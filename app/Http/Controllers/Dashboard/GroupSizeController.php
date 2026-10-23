<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\GroupSizeDataTable;
use App\Events\ResourceCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\GroupSizeRequest;
use App\Models\GroupSize;

class GroupSizeController extends Controller
{
    public function index(GroupSizeDataTable $dataTable)
    {
        return $dataTable->render('dashboard.group-sizes.index');
    }

    public function create()
    {
        return view('dashboard.group-sizes.create');
    }

    public function store(GroupSizeRequest $request)
    {
        $size = GroupSize::create($request->getSanitized());
        session()->flash('message', 'Group Size Created Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($size);
        return redirect()->route('dashboard.group-sizes.edit', $size);
    }

    public function edit(GroupSize $groupSize)
    {
        return view('dashboard.group-sizes.edit', compact('groupSize'));
    }

    public function update(GroupSizeRequest $request, GroupSize $groupSize)
    {
        $groupSize->update($request->getSanitized());
        session()->flash('message', 'Group Size Updated Successfully!');
        session()->flash('type', 'success');
        ResourceCreatedEvent::dispatch($groupSize);
        return back();
    }

    public function destroy(GroupSize $groupSize)
    {
        $groupSize->delete();
        return response()->json([
            'message' => 'Group Size Deleted Successfully!'
        ]);
    }
}
