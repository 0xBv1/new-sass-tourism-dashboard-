<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UserRequest;
use App\Models\Country;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{

    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('dashboard.users.index');
    }


    public function create()
    {
        $roles = Role::all();
        $countries_phone_codes = Country::select(['name', 'phone_code'])->get()->map(fn(Country $country) => [
            'id' => $country->phone_code,
            'name' => '(' . $country->phone_code . ') ' . $country->name
        ])->toArray();
        return view('dashboard.users.create', compact('roles', 'countries_phone_codes'));
    }


    public function store(UserRequest $request)
    {
        $user = User::create($request->getSanitized());
        $user->assignRole($request->get('roles'));
        session()->flash('message', 'User Created Successfully!');
        session()->flash('type', 'success');
        return redirect()->route('dashboard.users.edit', $user);
    }


    public function show(User $user)
    {
        //
    }


    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::all();
        $countries_phone_codes = Country::select(['name', 'phone_code'])->get()->map(fn(Country $country) => [
            'id' => $country->phone_code,
            'name' => '(' . $country->phone_code . ') ' . $country->name
        ])->toArray();
        return view('dashboard.users.edit', compact('user', 'countries_phone_codes', 'roles'));
    }


    public function update(UserRequest $request, User $user)
    {
        $user->update($request->getSanitized());
        $user->syncRoles($request->get('roles'));
        session()->flash('message', 'User Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'User Deleted Successfully!'
        ]);
    }
}
