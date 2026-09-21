<?php

namespace Database\Factories;

use App\Enums\EnrollmentStatus;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentEnrollment>
 */
class StudentEnrollmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'student_id' => Student::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'class_id' => SchoolClass::factory(),
            'status' => EnrollmentStatus::ACTIVE,
            'enrolled_at' => now(),
        ];
    }
}