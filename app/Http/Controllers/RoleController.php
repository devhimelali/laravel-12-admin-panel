<?php

namespace App\Http\Controllers;

use App\DataTables\RoleDataTable;
use App\Http\Requests\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('backend.roles.index');
    }

    public function store(RoleRequest $request)
    {
        Role::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully.'
        ]);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Role fetched successfully.',
            'data' => $role
        ]);
    }

    public function update(RoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Role updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role deleted successfully.'
        ]);
    }

    public function permissions(Role $role)
    {
        $permissions = Permission::where('guard_name', $role->guard_name)
            ->orderBy('group_name')
            ->orderBy('name')
            ->get()
            ->groupBy('group_name');

        return view('backend.roles.permissions', compact('role', 'permissions'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $permissionIds = array_map('intval', $request->input('permissions', []));
        $role->syncPermissions($permissionIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions updated successfully.',
        ]);
    }
}
