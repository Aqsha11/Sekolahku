<?php

namespace Database\Seeders;

use App\Enums\RoleSlug;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [RoleSlug::SUPER_ADMIN, 'Super Admin', 'Platform-wide administrative access', true],
            [RoleSlug::PLATFORM_ADMIN, 'Platform Admin', 'Managed platform-level resources', true],
            [RoleSlug::ORGANIZATION_ADMIN, 'Organization Admin', 'Administrator of an organization', true],
            [RoleSlug::SCHOOL_ADMIN, 'School Admin', 'Administrator of a school', true],
            [RoleSlug::HEADMASTER, 'Headmaster', 'Kepala sekolah school-level overview', true],
            [RoleSlug::HOMEROOM_TEACHER, 'Homeroom Teacher', 'Wali kelas', true],
            [RoleSlug::TEACHER, 'Teacher', 'Guru mata pelajaran', true],
            [RoleSlug::COUNSELOR, 'Counselor', 'Guru BK', true],
            [RoleSlug::STAFF, 'Staff', 'Staf administrasi sekolah', true],
            [RoleSlug::STUDENT, 'Student', 'Siswa', true],
            [RoleSlug::PARENT, 'Parent', 'Orang tua / wali siswa', true],
        ];

        foreach ($roles as [$slug, $name, $description, $isSystem]) {
            Role::updateOrCreate(
                ['slug' => $slug->value],
                [
                    'name' => $name,
                    'description' => $description,
                    'is_system' => $isSystem,
                ],
            );
        }
    }
}