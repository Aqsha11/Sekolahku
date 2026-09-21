<?php

namespace App\Models;

use App\Enums\LessonSessionStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LessonSession extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'school_id',
        'schedule_id',
        'teacher_id',
        'class_id',
        'subject_id',
        'date',
        'start_at',
        'end_at',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'status' => LessonSessionStatus::class,
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function attendance(): HasMany
    {
        return $this->hasMany(LessonAttendance::class);
    }
}