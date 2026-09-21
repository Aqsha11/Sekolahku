<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Support\PermissionCatalog;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $matrix = PermissionCatalog::matrix();

        foreach ($matrix as $slug => $permissions) {
            $role = Role::where('slug', $slug)->firstOrFail();

            $ids = Permission::whereIn('slug', $permissions)->pluck('id');

            $role->permissions()->sync($ids);
        }
    }
}