<?php

namespace App\Http\Controllers\backend;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        $roles = Role::where('is_active', 1)->get();
        return $dataTable->render('backend.users.index', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());
        $user->assignRole($request->role);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.'
        ]);
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'message' => 'User fetched successfully.',
            'data' => $user
        ]);
    }
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = collect($request->validated())->except(['password', 'confirm_password'])->toArray();
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }
        $user->update($data);
        $user->syncRoles([$request->role]);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully.'
        ]);
    }
}
