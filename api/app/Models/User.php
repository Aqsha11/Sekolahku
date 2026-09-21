<?php

namespace App\Models;

use App\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasUuids, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'status' => UserStatus::class,
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(SchoolMembership::class);
    }

    public function schools(): BelongsToMany
    {
        return $this->belongsToMany(School::class, 'school_memberships')
            ->using(SchoolMembership::class)
            ->withPivot('status', 'joined_at')
            ->withTimestamps();
    }

    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function teacherProfiles(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function parentProfiles(): HasMany
    {
        return $this->hasMany(Guardian::class);
    }

    public function hasRole(string|array $slug): bool
    {
        return $this->roles()->whereIn('slug', (array) $slug)->exists();
    }

    public function isPlatformUser(): bool
    {
        return $this->hasRole(['super_admin', 'platform_admin']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function rolesForSchool(?string $schoolId = null): Collection
    {
        $roleIds = $this->userRoles()
            ->when($schoolId, fn ($q) => $q->where(
                fn ($qq) => $qq->where('school_id', $schoolId)->orWhereNull('school_id')
            ))
            ->when(! $schoolId, fn ($q) => $q->whereNull('school_id'))
            ->pluck('role_id');

        return Role::whereIn('id', $roleIds)->get([
            'id',
            'name',
            'slug',
            'is_system',
        ]);
    }

    public function permissionsForSchool(?string $schoolId = null): Collection
    {
        return $this->rolesForSchool($schoolId)
            ->flatMap->permissions
            ->pluck('slug')
            ->unique()
            ->values();
    }

    public function hasPermission(string $permission, ?string $schoolId = null): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->permissionsForSchool($schoolId)->contains($permission);
    }

    public function assignRole(string|Role $role, ?string $schoolId = null): UserRole
    {
        $roleId = $role instanceof Role
            ? $role->id
            : Role::where('slug', $role)->valueOrFail('id');

        return UserRole::updateOrCreate(
            ['user_id' => $this->id, 'school_id' => $schoolId, 'role_id' => $roleId],
        );
    }
}