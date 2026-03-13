<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            [
                'name' => 'dashboard.view',
                'guard_name' => 'web',
                'group_name' => 'dashboard',
                'display_name' => 'View Dashboard',
                'description' => 'Access dashboard',
            ],
            // Users
            [
                'name' => 'users.view',
                'guard_name' => 'web',
                'group_name' => 'users',
                'display_name' => 'View Users',
                'description' => 'View users list',
            ],
            [
                'name' => 'users.create',
                'guard_name' => 'web',
                'group_name' => 'users',
                'display_name' => 'Create User',
                'description' => 'Create new users',
            ],
            [
                'name' => 'users.edit',
                'guard_name' => 'web',
                'group_name' => 'users',
                'display_name' => 'Edit User',
                'description' => 'Edit existing users',
            ],
            [
                'name' => 'users.delete',
                'guard_name' => 'web',
                'group_name' => 'users',
                'display_name' => 'Delete User',
                'description' => 'Delete users',
            ],
            // Roles
            [
                'name' => 'roles.view',
                'guard_name' => 'web',
                'group_name' => 'roles',
                'display_name' => 'View Roles',
                'description' => 'View roles list',
            ],
            [
                'name' => 'roles.create',
                'guard_name' => 'web',
                'group_name' => 'roles',
                'display_name' => 'Create Role',
                'description' => 'Create new roles',
            ],
            [
                'name' => 'roles.edit',
                'guard_name' => 'web',
                'group_name' => 'roles',
                'display_name' => 'Edit Role',
                'description' => 'Edit existing roles',
            ],
            [
                'name' => 'roles.delete',
                'guard_name' => 'web',
                'group_name' => 'roles',
                'display_name' => 'Delete Role',
                'description' => 'Delete roles',
            ],
            [
                'name' => 'roles.permissions',
                'guard_name' => 'web',
                'group_name' => 'roles',
                'display_name' => 'Manage Permissions',
                'description' => 'Assign permissions to roles',
            ],
            // Settings
            [
                'name' => 'settings.view',
                'guard_name' => 'web',
                'group_name' => 'settings',
                'display_name' => 'View Settings',
                'description' => 'Access settings',
            ],
            [
                'name' => 'settings.edit',
                'guard_name' => 'web',
                'group_name' => 'settings',
                'display_name' => 'Edit Settings',
                'description' => 'Modify settings',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                $permission
            );
        }
    }
}
