<?php

namespace App\Models;

use App\Enums\ParentRelationship;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentStudent extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'parent_id',
        'student_id',
        'relationship',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'relationship' => ParentRelationship::class,
            'is_primary' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Guardian::class, 'parent_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}