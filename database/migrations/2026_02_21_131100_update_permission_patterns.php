<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            if (count($parts) === 2 && in_array($parts[0], ['view', 'create', 'edit', 'delete'])) {
                // Currently format is action.resource
                $newName = "{$parts[1]}.{$parts[0]}";
                DB::table('permissions')->where('id', $permission->id)->update(['name' => $newName]);
            }
        }
        
        // Clear spatie cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            // Revert back config if currently format is resource.action
            if (count($parts) === 2 && in_array($parts[1], ['view', 'create', 'edit', 'delete'])) {
                $newName = "{$parts[1]}.{$parts[0]}";
                DB::table('permissions')->where('id', $permission->id)->update(['name' => $newName]);
            }
        }
        
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
