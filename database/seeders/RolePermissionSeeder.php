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

                // Editor role gets all permissions for content, but cannot delete anything and cannot manage users, roles, or permissions
                if (!in_array($resource, ['users', 'roles', 'permissions']) && $action !== 'delete') {
                    $editorPermissions[] = $permissionName;
                }
            }
        }

        // Create Roles
        $superAdmin = Role::findOrCreate('super-admin');
        $editor = Role::findOrCreate('admin');

        // Assign all permissions to super-admin
        $superAdmin->syncPermissions($allPermissions);

        // Assign specific permissions to editor
        $editor->syncPermissions($editorPermissions);

        // Create the initial admin user if not exists
        $user = User::firstOrCreate(
            ['email' => 'super@ktorzem.com'],
            [
                'name' => 'Developer',
                'password' => Hash::make('password123'),
            ]
        );

        // Assign the super-admin role
        $user->assignRole($superAdmin);
    }
}
