<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\RecordStatus;
use App\Models\AcademicYear;
use App\Models\Room;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjectAssignment;
use Illuminate\Database\Seeder;

class DemoClassSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::where('school_code', 'SD-DEMO-01')->firstOrFail();

        $year = AcademicYear::where('school_id', $school->id)
            ->where('name', '2026/2027')
            ->firstOrFail();

        $teachers = $this->ensureTeachers($school);

        $subjects = Subject::where('school_id', $school->id)
            ->whereIn('code', ['MTK', 'BIND'])
            ->get()
            ->keyBy('code');

        for ($grade = 1; $grade <= 6; $grade++) {
            $teacher = $teachers[$grade - 1];
            $room = Room::where('school_id', $school->id)
                ->where('code', sprintf('RM-%02d', $grade))
                ->firstOrFail();

            $class = SchoolClass::updateOrCreate(
                [
                    'school_id' => $school->id,
                    'academic_year_id' => $year->id,
                    'name' => $grade.'A',
                ],
                [
                    'grade_level' => (string) $grade,
                    'homeroom_teacher_id' => $teacher->id,
                    'room_id' => $room->id,
                    'capacity' => 30,
                    'status' => RecordStatus::ACTIVE,
                ],
            );

            foreach ($subjects as $subject) {
                TeacherSubjectAssignment::updateOrCreate(
                    [
                        'teacher_id' => $teacher->id,
                        'subject_id' => $subject->id,
                        'class_id' => $class->id,
                        'academic_year_id' => $year->id,
                    ],
                    ['school_id' => $school->id],
                );
            }
        }
    }

    /**
     * @return array<int, Teacher>
     */
    private function ensureTeachers(School $school): array
    {
        $teachers = [
            ['G-001', 'Rina Marlina', Gender::FEMALE],
            ['G-002', 'Budi Santoso', Gender::MALE],
            ['G-003', 'Siti Nurhaliza', Gender::FEMALE],
            ['G-004', 'Dedi Susanto', Gender::MALE],
            ['G-005', 'Novi Andriani', Gender::FEMALE],
            ['G-006', 'Hendra Gunawan', Gender::MALE],
        ];

        $results = [];

        foreach ($teachers as [$employeeNumber, $name, $gender]) {
            $teacher = Teacher::where('school_id', $school->id)
                ->where('employee_number', $employeeNumber)
                ->first();

            $results[] = Teacher::updateOrCreate(
                ['school_id' => $school->id, 'employee_number' => $employeeNumber],
                [
                    'nip' => null,
                    'name' => $name,
                    'gender' => $gender,
                    'birth_place' => 'Makassar',
                    'birth_date' => now()->subYears(rand(30, 50))->toDateString(),
                    'phone' => '081234567880',
                    'email' => $teacher?->user_id
                        ? $teacher->user->email
                        : strtolower(str_replace(' ', '', $name)).'@example.test',
                    'address' => 'Jl. Pendidikan No. 1, Makassar',
                    'status' => RecordStatus::ACTIVE,
                ],
            );
        }

        return $results;
    }
}