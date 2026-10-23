<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\ClientDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ClientRequest;
use App\Models\Client;

class ClientController extends Controller
{
    public function index(ClientDataTable $dataTable)
    {
        return $dataTable->render('dashboard.clients.index');
    }


    public function create()
    {
        return view('dashboard.clients.create');
    }


    public function store(ClientRequest $request)
    {
        Client::create($request->getSanitized());
        session()->flash('message', 'Client Created Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function show(Client $client)
    {
        //
    }


    public function edit(Client $client)
    {
        return view('dashboard.clients.edit', compact('client'));
    }


    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->getSanitized());
        session()->flash('message', 'Client Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json([
            'message' => 'Client Deleted Successfully!'
        ]);
    }
}
