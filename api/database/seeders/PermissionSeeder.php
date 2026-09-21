<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Support\PermissionCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionCatalog::modules() as $module => $actions) {
            foreach ($actions as $action) {
                Permission::updateOrCreate(
                    [
                        'module' => $module,
                        'action' => $action,
                    ],
                    [
                        'name' => Str::headline($action).' '.Str::headline($module),
                        'slug' => "$module.$action",
                    ],
                );
            }
        }
    }
}