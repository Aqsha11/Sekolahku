<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolSetting extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'school_id',
        'timezone',
        'currency',
        'attendance_late_threshold',
        'attendance_start_time',
        'attendance_end_time',
        'enable_parent_notification',
        'enable_teacher_notification',
        'enable_whatsapp',
        'enable_email',
        'enable_push',
    ];

    protected function casts(): array
    {
        return [
            'attendance_start_time' => 'datetime:H:i',
            'attendance_end_time' => 'datetime:H:i',
            'enable_parent_notification' => 'boolean',
            'enable_teacher_notification' => 'boolean',
            'enable_whatsapp' => 'boolean',
            'enable_email' => 'boolean',
            'enable_push' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}