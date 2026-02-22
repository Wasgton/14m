<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define resources and actions for granular permissions
        $resources = [
            'banners',
            'events',
            'artists',
            'partners',
            'users',
            'roles',
            'permissions',
            'settings'
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        $allPermissions = [];
        $editorPermissions = [];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissionName = "{$resource}.{$action}";
                Permission::findOrCreate($permissionName);
                $allPermissions[] = $permissionName;

                if (!in_array($resource, ['permissions'])) {
                    $editorPermissions[] = $permissionName;
                }
            }
        }

        $superAdmin = Role::findOrCreate('super-admin');
        $admin = Role::findOrCreate('admin');

        $superAdmin->syncPermissions($allPermissions);

        $admin->syncPermissions($editorPermissions);

        // Create the initial super admin user if not exists
        $user = User::firstOrCreate(
            ['email' => 'super@ktorzem.com'],
            [
                'name' => 'Developer',
                'password' => Hash::make('password123'),
            ]
        );

        $user->assignRole($superAdmin);
    }
}
