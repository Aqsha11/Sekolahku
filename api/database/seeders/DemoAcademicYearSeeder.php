<?php

namespace Database\Seeders;

use App\Enums\AcademicYearStatus;
use App\Enums\SemesterStatus;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class DemoAcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::where('school_code', 'SD-DEMO-01')->firstOrFail();

        $year = AcademicYear::updateOrCreate(
            ['school_id' => $school->id, 'name' => '2026/2027'],
            [
                'start_date' => '2026-07-01',
                'end_date' => '2027-06-30',
                'status' => AcademicYearStatus::ACTIVE,
            ],
        );

        Semester::updateOrCreate(
            ['academic_year_id' => $year->id, 'sequence' => 1],
            [
                'name' => 'Ganjil',
                'start_date' => '2026-07-01',
                'end_date' => '2026-12-31',
                'status' => SemesterStatus::ACTIVE,
            ],
        );

        Semester::updateOrCreate(
            ['academic_year_id' => $year->id, 'sequence' => 2],
            [
                'name' => 'Genap',
                'start_date' => '2027-01-01',
                'end_date' => '2027-06-30',
                'status' => SemesterStatus::INACTIVE,
            ],
        );
    }
}