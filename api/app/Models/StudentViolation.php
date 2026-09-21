<?php

namespace App\Models;

use App\Enums\ViolationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentViolation extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'school_id',
        'student_id',
        'violation_type_id',
        'reported_by',
        'occurred_at',
        'description',
        'points',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
            'points' => 'integer',
            'status' => ViolationStatus::class,
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function violationType(): BelongsTo
    {
        return $this->belongsTo(ViolationType::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}