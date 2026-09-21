<?php

namespace Database\Factories;

use App\Enums\RecordStatus;
use App\Models\AcademicYear;
use App\Models\Room;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<SchoolClass>
 */
class SchoolClassFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'name' => Str::upper(fake()->randomLetter()).'-'.fake()->numberBetween(1, 9),
            'grade_level' => (string) fake()->numberBetween(1, 9),
            'homeroom_teacher_id' => Teacher::factory(),
            'room_id' => Room::factory(),
            'capacity' => fake()->numberBetween(25, 40),
            'status' => RecordStatus::ACTIVE,
        ];
    }
}