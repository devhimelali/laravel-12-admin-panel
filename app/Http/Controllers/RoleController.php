<?php

namespace App\Http\Controllers;

use App\DataTables\RoleDataTable;
use App\Http\Requests\RoleRequest;
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
}
