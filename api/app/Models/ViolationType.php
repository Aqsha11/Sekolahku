<?php

namespace App\Models;

use App\Enums\RecordStatus;
use App\Enums\ViolationSeverity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViolationType extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'school_id',
        'name',
        'description',
        'severity',
        'points',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'severity' => ViolationSeverity::class,
            'points' => 'integer',
            'status' => RecordStatus::class,
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}