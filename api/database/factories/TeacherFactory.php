<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\RecordStatus;
use App\Models\School;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'employee_number' => fn () => fake()->unique()->numerify('EMP#####'),
            'nip' => fake()->numerify('##################'),
            'name' => fake()->name(),
            'gender' => fake()->randomElement([Gender::MALE, Gender::FEMALE]),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->date('Y-m-d', '-30 years'),
            'phone' => fake()->numerify('08##########'),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->streetAddress(),
            'status' => RecordStatus::ACTIVE,
        ];
    }
}