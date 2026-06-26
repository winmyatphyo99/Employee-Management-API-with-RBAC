<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Create Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            // Employee
            'employee.view',
            'employee.create',
            'employee.update',
            'employee.delete',

            // Department
            'department.view',
            'department.create',
            'department.update',
            'department.delete',

            // User
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
        ];

        $permissionModels = [];

        foreach ($permissions as $permission) {
            $permissionModels[$permission] = Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Create Roles
        |--------------------------------------------------------------------------
        */

        $roles = [
            'Super Admin',
            'Admin',
            'HR',
            'Manager',
            'Employee'
        ];

        $roleModels = [];

        foreach ($roles as $role) {
            $roleModels[$role] = Role::firstOrCreate([
                'name' => $role
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Attach Permissions to Roles
        |--------------------------------------------------------------------------
        */

        // Super Admin → ALL permissions
        $roleModels['Super Admin']->permissions()->sync(
            array_values($permissionModels)
        );

        // Admin → full control except user.delete (example business rule)
        $roleModels['Admin']->permissions()->sync([
            $permissionModels['employee.view']->id,
            $permissionModels['employee.create']->id,
            $permissionModels['employee.update']->id,
            $permissionModels['employee.delete']->id,

            $permissionModels['department.view']->id,
            $permissionModels['department.create']->id,
            $permissionModels['department.update']->id,
            $permissionModels['department.delete']->id,

            $permissionModels['user.view']->id,
            $permissionModels['user.create']->id,
            $permissionModels['user.update']->id,
        ]);

        // HR → employee management only
        $roleModels['HR']->permissions()->sync([
            $permissionModels['employee.view']->id,
            $permissionModels['employee.create']->id,
            $permissionModels['employee.update']->id,
        ]);

        // Manager → view only
        $roleModels['Manager']->permissions()->sync([
            $permissionModels['employee.view']->id,
            $permissionModels['department.view']->id,
        ]);

        // Employee → self access (minimal)
        $roleModels['Employee']->permissions()->sync([
            $permissionModels['employee.view']->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4. Assign Super Admin Role to First User
        |--------------------------------------------------------------------------
        */

        $user = User::first();

        if ($user) {
            $user->roles()->syncWithoutDetaching([
                $roleModels['Super Admin']->id
            ]);
        }
    }
}
