<?php

namespace Database\Seeders;

use App\Enums\RecordStatus;
use App\Enums\SchoolLevel;
use App\Enums\SchoolStatus;
use App\Models\Organization;
use App\Models\Room;
use App\Models\School;
use App\Models\SchoolSetting;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class DemoSchoolSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::where('slug', 'yayasan-disekolahku')->firstOrFail();

        $school = School::updateOrCreate(
            ['organization_id' => $organization->id, 'school_code' => 'SD-DEMO-01'],
            [
                'npsn' => '12345678',
                'name' => 'SD Demo DiSekolahKu',
                'level' => SchoolLevel::SD,
                'address' => 'Jl. Pendidikan No. 1, Makassar',
                'province' => 'Sulawesi Selatan',
                'city' => 'Makassar',
                'district' => 'Tamalate',
                'village' => 'Mangasa',
                'postal_code' => '90225',
                'phone' => '0411-123456',
                'email' => 'sd-demo@disekolahku.id',
                'website' => 'https://demo.disekolahku.id',
                'status' => SchoolStatus::ACTIVE,
            ],
        );

        SchoolSetting::updateOrCreate(
            ['school_id' => $school->id],
            [
                'timezone' => 'Asia/Makassar',
                'currency' => 'IDR',
                'attendance_late_threshold' => 15,
                'attendance_start_time' => '07:00:00',
                'attendance_end_time' => '12:00:00',
                'enable_parent_notification' => true,
                'enable_teacher_notification' => true,
                'enable_whatsapp' => true,
                'enable_email' => true,
                'enable_push' => false,
            ],
        );

        $rooms = [
            ['RM-01', 'Ruang Kelas 1A', 30],
            ['RM-02', 'Ruang Kelas 2A', 30],
            ['RM-03', 'Ruang Kelas 3A', 30],
            ['RM-04', 'Ruang Kelas 4A', 30],
            ['RM-05', 'Ruang Kelas 5A', 30],
            ['RM-06', 'Ruang Kelas 6A', 30],
        ];

        foreach ($rooms as [$code, $name, $capacity]) {
            Room::updateOrCreate(
                ['school_id' => $school->id, 'code' => $code],
                [
                    'name' => $name,
                    'capacity' => $capacity,
                    'description' => null,
                    'status' => RecordStatus::ACTIVE,
                ],
            );
        }

        $subjects = [
            ['MTK', 'Matematika'],
            ['BIND', 'Bahasa Indonesia'],
            ['BING', 'Bahasa Inggris'],
            ['IPA', 'Ilmu Pengetahuan Alam'],
            ['IPS', 'Ilmu Pengetahuan Sosial'],
            ['PJOK', 'Pendidikan Jasmani Olahraga dan Kesehatan'],
            ['PAI', 'Pendidikan Agama Islam'],
            ['SBK', 'Seni Budaya dan Keterampilan'],
        ];

        foreach ($subjects as [$code, $name]) {
            Subject::updateOrCreate(
                ['school_id' => $school->id, 'code' => $code],
                [
                    'name' => $name,
                    'description' => null,
                    'status' => RecordStatus::ACTIVE,
                ],
            );
        }
    }
}