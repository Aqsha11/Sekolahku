<?php

namespace Database\Factories;

use App\Enums\SchoolLevel;
use App\Enums\SchoolStatus;
use App\Models\Organization;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<School>
 */
class SchoolFactory extends Factory
{
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'school_code' => fn () => Str::upper(Str::random(6)),
            'npsn' => fake()->unique()->numerify('#########'),
            'name' => 'SDN '.fake()->cityName(),
            'level' => SchoolLevel::SD,
            'address' => fake()->streetAddress(),
            'province' => 'Sulawesi Selatan',
            'city' => 'Makassar',
            'district' => fake()->citySuffix(),
            'village' => fake()->streetName(),
            'postal_code' => fake()->postcode(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'website' => null,
            'status' => SchoolStatus::ACTIVE,
        ];
    }
}