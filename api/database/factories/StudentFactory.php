<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\RecordStatus;
use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        $gender = fake()->randomElement([Gender::MALE, Gender::FEMALE]);

        return [
            'school_id' => School::factory(),
            'nis' => fn (array $attributes) => fake()->unique()->numerify('#########'),
            'nisn' => fn (array $attributes) => fake()->unique()->numerify('##########'),
            'name' => fake()->name(fake()->randomElement(['male', 'female'])),
            'gender' => $gender,
            'birth_place' => fake()->city(),
            'birth_date' => fake()->date('Y-m-d', '-12 years'),
            'nik' => fake()->numerify('################'),
            'religion' => fake()->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
            'phone' => fake()->numerify('08##########'),
            'address' => fake()->streetAddress(),
            'status' => RecordStatus::ACTIVE,
        ];
    }
}