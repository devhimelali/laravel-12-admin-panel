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
}
