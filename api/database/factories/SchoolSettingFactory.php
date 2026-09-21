<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\SchoolSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SchoolSetting>
 */
class SchoolSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'timezone' => 'Asia/Makassar',
            'currency' => 'IDR',
            'attendance_late_threshold' => 15,
            'attendance_start_time' => '07:00',
            'attendance_end_time' => '15:00',
            'enable_parent_notification' => true,
            'enable_teacher_notification' => true,
            'enable_whatsapp' => false,
            'enable_email' => false,
            'enable_push' => false,
        ];
    }
}