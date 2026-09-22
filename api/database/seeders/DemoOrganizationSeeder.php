<?php

namespace Database\Seeders;

use App\Enums\OrganizationStatus;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class DemoOrganizationSeeder extends Seeder
{
    public function run(): void
    {
        Organization::updateOrCreate(
            ['slug' => 'yayasan-disekolahku'],
            [
                'name' => 'Yayasan DiSekolahKu',
                'status' => OrganizationStatus::ACTIVE,
            ],
        );
    }
}