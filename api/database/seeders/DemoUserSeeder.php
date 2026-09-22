<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\MembershipStatus;
use App\Enums\RecordStatus;
use App\Enums\RoleSlug;
use App\Enums\UserStatus;
use App\Models\Guardian;
use App\Models\School;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::where('school_code', 'SD-DEMO-01')->firstOrFail();

        $password = 'password';

        $this->platformUser('admin@example.test', 'Administrator Platform', $password, RoleSlug::SUPER_ADMIN);

        $this->schoolUser('schooladmin@example.test', 'Admin SD Demo DiSekolahKu', $password, RoleSlug::SCHOOL_ADMIN, $school);

        $headmaster = $this->schoolUser('headmaster@example.test', 'Rina Marlina', $password, RoleSlug::HEADMASTER, $school);
        $this->teacherProfile($school, $headmaster, 'G-001', 'Rina Marlina', Gender::FEMALE);

        $teacher = $this->schoolUser('teacher@example.test', 'Budi Santoso', $password, RoleSlug::TEACHER, $school);
        $this->teacherProfile($school, $teacher, 'G-002', 'Budi Santoso', Gender::MALE);

        $parent = $this->schoolUser('parent@example.test', 'Ahmad Fauzi', $password, RoleSlug::PARENT, $school);

        Guardian::updateOrCreate(
            ['school_id' => $school->id, 'user_id' => $parent->id],
            [
                'father_name' => 'Ahmad Fauzi',
                'mother_name' => 'Siti Aminah',
                'phone' => '081234567889',
                'email' => 'parent@example.test',
                'address' => 'Jl. Melati No. 5, Makassar',
            ],
        );
    }

    private function platformUser(string $email, string $name, string $password, RoleSlug $role): User
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'phone' => null,
                'password' => Hash::make($password),
                'status' => UserStatus::ACTIVE,
                'email_verified_at' => now(),
            ],
        );

        $user->assignRole($role->value);

        return $user;
    }

    private function schoolUser(string $email, string $name, string $password, RoleSlug $role, School $school): User
    {
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'phone' => null,
                'password' => Hash::make($password),
                'status' => UserStatus::ACTIVE,
                'email_verified_at' => now(),
            ],
        );

        $user->assignRole($role->value, $school->id);

        $user->schools()->syncWithoutDetaching([
            $school->id => [
                'status' => MembershipStatus::ACTIVE,
                'joined_at' => now(),
            ],
        ]);

        return $user;
    }

    private function teacherProfile(School $school, User $user, string $employeeNumber, string $name, Gender $gender): Teacher
    {
        return Teacher::updateOrCreate(
            ['school_id' => $school->id, 'employee_number' => $employeeNumber],
            [
                'user_id' => $user->id,
                'nip' => null,
                'name' => $name,
                'gender' => $gender,
                'birth_place' => 'Makassar',
                'birth_date' => now()->subYears(rand(30, 50))->toDateString(),
                'phone' => '081234567887',
                'email' => $user->email,
                'address' => 'Jl. Pendidikan No. 1, Makassar',
                'status' => RecordStatus::ACTIVE,
            ],
        );
    }
}