<?php

namespace Database\Factories;

use App\Enums\RecordStatus;
use App\Models\Room;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'name' => 'Ruang Kelas '.fake()->numberBetween(1, 20),
            'code' => fn (array $attributes) => strtoupper(substr(str_replace(' ', '', $attributes['name']), 0, 6)),
            'capacity' => fake()->numberBetween(25, 40),
            'description' => null,
            'status' => RecordStatus::ACTIVE,
        ];
    }
}