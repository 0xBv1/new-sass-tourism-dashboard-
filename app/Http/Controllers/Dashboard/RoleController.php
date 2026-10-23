<?php

namespace App\Http\Controllers\Dashboard;

use App\DataTables\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\RoleRequest;
use App\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    private function loadPermissionsGrouped()
    {
        $all_permissions = Permission::all();
        $permissions = [];
        foreach ($all_permissions as $permission) {
            $permission_group = explode('.', $permission->name)[0];
            $permissions[$permission_group][] = $permission;
        }
        return $permissions;
    }

    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('dashboard.roles.index');
    }


    public function create()
    {
        $groupedPermissions = $this->loadPermissionsGrouped();
        return view('dashboard.roles.create', compact('groupedPermissions'));
    }


    public function store(RoleRequest $request)
    {
        $role = Role::create($request->only('name'));
        $role->givePermissionTo($request->getPermissions());
        session()->flash('message', 'Role Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function show(Role $role)
    {
        //
    }


    public function edit(Role $role)
    {
        $groupedPermissions = $this->loadPermissionsGrouped();
        $role->load('permissions:id');
        return view('dashboard.roles.edit', compact('role', 'groupedPermissions'));
    }


    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->only('name'));
        $role->syncPermissions($request->getPermissions());
        session()->flash('message', 'Role Updated Successfully!');
        session()->flash('type', 'success');
        return back();
    }


    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            'message' => 'Role Deleted Successfully!'
        ]);
    }
}
