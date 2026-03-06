<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administration',
                'is_active' => true
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'is_active' => true
            ]
        ];

        foreach ($roles as $role){
            Role::create($role);
        }
    }
}
