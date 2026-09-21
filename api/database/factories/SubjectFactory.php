<?php

namespace Database\Factories;

use App\Enums\RecordStatus;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Subject>
 */
class SubjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'code' => fn () => Str::upper(Str::random(4)),
            'name' => fake()->randomElement(['Matematika', 'Bahasa Indonesia', 'IPA', 'IPS', 'Bahasa Inggris', 'Pendidikan Agama', 'Seni Budaya', 'PJOK']),
            'description' => null,
            'status' => RecordStatus::ACTIVE,
        ];
    }
}