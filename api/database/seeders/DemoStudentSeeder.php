<?php

namespace Database\Seeders;

use App\Enums\EnrollmentStatus;
use App\Enums\Gender;
use App\Enums\MembershipStatus;
use App\Enums\ParentRelationship;
use App\Enums\RecordStatus;
use App\Enums\RoleSlug;
use App\Enums\UserStatus;
use App\Models\AcademicYear;
use App\Models\Guardian;
use App\Models\ParentStudent;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoStudentSeeder extends Seeder
{
    private const FIRST_NAMES = [
        'Andi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita',
        'Hadi', 'Intan', 'Joko', 'Kirana', 'Lutfi', 'Maya',
        'Nanda', 'Omar', 'Putri', 'Rizky', 'Sari', 'Teuku',
    ];

    private const LAST_NAMES = [
        'Pratama', 'Wijaya', 'Ningsih', 'Saputri', 'Ramadhan', 'Hidayat',
        'Kusuma', 'Santoso', 'Lestari', 'Maulana', 'Putri', 'Hakim',
        'Anggraini', 'Firdaus', 'Salsabila', 'Mahendra', 'Utami', 'Habibi',
    ];

    public function run(): void
    {
        $school = School::where('school_code', 'SD-DEMO-01')->firstOrFail();

        $year = AcademicYear::where('school_id', $school->id)
            ->where('name', '2026/2027')
            ->firstOrFail();

        $classes = SchoolClass::where('school_id', $school->id)
            ->where('academic_year_id', $year->id)
            ->orderBy('grade_level')
            ->get();

        $demoParent = User::where('email', 'parent@example.test')->firstOrFail();

        $demoParentGuardian = Guardian::where('school_id', $school->id)
            ->where('user_id', $demoParent->id)
            ->firstOrFail();

        $awardedNames = [];
        $parentUserIndex = 2;

        foreach ($classes as $class) {
            $grade = (int) $class->grade_level;

            for ($slot = 1; $slot <= 3; $slot++) {
                $studentNumber = count($awardedNames) + 1;
                $studentName = $this->studentName($studentNumber, $awardedNames);
                $awardedNames[$studentNumber] = $studentName;

                $student = Student::updateOrCreate(
                    ['school_id' => $school->id, 'nis' => sprintf('2026%04d', $studentNumber)],
                    [
                        'nisn' => sprintf('%010d', 10000000 + $studentNumber),
                        'name' => $studentName,
                        'gender' => $studentNumber % 2 === 0 ? Gender::FEMALE : Gender::MALE,
                        'birth_place' => 'Makassar',
                        'birth_date' => now()->startOfYear()->subYears($grade + 6)->toDateString(),
                        'nik' => sprintf('%016d', rand(0, 9999999999999999)),
                        'religion' => 'Islam',
                        'phone' => sprintf('08123%06d', $studentNumber),
                        'address' => 'Jl. Melati No. '.$studentNumber.', Makassar',
                        'status' => RecordStatus::ACTIVE,
                    ],
                );

                StudentEnrollment::updateOrCreate(
                    ['student_id' => $student->id, 'academic_year_id' => $year->id],
                    [
                        'school_id' => $school->id,
                        'class_id' => $class->id,
                        'status' => EnrollmentStatus::ACTIVE,
                        'enrolled_at' => now(),
                        'ended_at' => null,
                    ],
                );

                $guardian = $studentNumber === 1
                    ? $demoParentGuardian
                    : $this->parentUser($school, $parentUserIndex++, $studentName);

                ParentStudent::updateOrCreate(
                    ['parent_id' => $guardian->id, 'student_id' => $student->id],
                    [
                        'relationship' => ParentRelationship::FATHER,
                        'is_primary' => true,
                    ],
                );
            }
        }
    }

    /**
     * @param  array<int, string>  $awarded
     */
    private function studentName(int $index, array $awarded): string
    {
        return $awarded[$index]
            ?? self::FIRST_NAMES[$index - 1].' '.self::LAST_NAMES[$index - 1];
    }

    private function parentUser(School $school, int $index, string $studentName): Guardian
    {
        $email = sprintf('ortu%d@example.test', $index);

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Orang Tua '.$studentName,
                'phone' => null,
                'password' => Hash::make('password'),
                'status' => UserStatus::ACTIVE,
                'email_verified_at' => now(),
            ],
        );

        $user->assignRole(RoleSlug::PARENT->value, $school->id);

        $user->schools()->syncWithoutDetaching([
            $school->id => [
                'status' => MembershipStatus::ACTIVE,
                'joined_at' => now(),
            ],
        ]);

        return Guardian::updateOrCreate(
            ['school_id' => $school->id, 'user_id' => $user->id],
            [
                'father_name' => $user->name,
                'mother_name' => 'Ibu '.$studentName,
                'phone' => '081234567890',
                'email' => $email,
                'address' => 'Jl. Melati No. '.($index + 1).', Makassar',
            ],
        );
    }
}