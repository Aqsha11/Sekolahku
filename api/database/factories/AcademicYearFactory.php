<?php

namespace Database\Factories;

use App\Enums\AcademicYearStatus;
use App\Models\AcademicYear;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AcademicYear>
 */
class AcademicYearFactory extends Factory
{
    public function definition(): array
    {
        $startYear = fake()->numberBetween(2020, 2026);
        $endYear = $startYear + 1;

        return [
            'school_id' => School::factory(),
            'name' => "{$startYear}/{$endYear}",
            'start_date' => "{$startYear}-07-01",
            'end_date' => "{$endYear}-06-30",
            'status' => AcademicYearStatus::INACTIVE,
        ];
    }
}