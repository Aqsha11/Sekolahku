<?php

namespace App\Models;

use App\Enums\BillingInterval;
use App\Enums\RecordStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_interval',
        'student_limit',
        'teacher_limit',
        'storage_limit',
        'features',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'billing_interval' => BillingInterval::class,
            'student_limit' => 'integer',
            'teacher_limit' => 'integer',
            'storage_limit' => 'integer',
            'features' => 'array',
            'status' => RecordStatus::class,
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}