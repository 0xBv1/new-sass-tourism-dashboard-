<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\SubscribeDataTable;
use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function index(SubscribeDataTable $dataTable)
    {
        return $dataTable->render('dashboard.subscribes.index');
    }

    public function create()
    {
        return view('dashboard.subscribes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribes,email',
            'name' => 'nullable|string|max:255',
        ]);

        Subscribe::create([
            'email' => $request->email,
            'name' => $request->name,
            'is_active' => true,
        ]);

        return redirect()->route('dashboard.subscribes.index')
            ->with('success', 'Subscriber added successfully.');
    }

    public function edit(Subscribe $subscribe)
    {
        return view('dashboard.subscribes.edit', compact('subscribe'));
    }

    public function update(Request $request, Subscribe $subscribe)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribes,email,' . $subscribe->id,
            'name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $subscribe->update($request->only(['email', 'name', 'is_active']));

        return redirect()->route('dashboard.subscribes.index')
            ->with('success', 'Subscriber updated successfully.');
    }

    public function destroy(Subscribe $subscribe)
    {
        $subscribe->delete();

        return back()->with('success', 'Subscriber deleted successfully.');
    }
}

