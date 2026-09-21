<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Support\SchoolContext;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    /**
     * @param  Request  $request
     */
    public function toArray($request): array
    {
        /** @var User $user */
        $user = $this->resource;

        $schoolId = $user->isPlatformUser()
            ? null
            : SchoolContext::schoolId();

        $roles = $user->rolesForSchool($schoolId);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'status' => $user->status,
            'is_platform_user' => $user->isPlatformUser(),
            'is_super_admin' => $user->isSuperAdmin(),
            'roles' => $roles->map(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
            ])->values(),
            'permissions' => $user->permissionsForSchool($schoolId)->values(),
            'schools' => $user->schools->map(function ($school) use ($user) {
                $schoolRoles = $user->rolesForSchool($school->id);

                return [
                    'id' => $school->id,
                    'name' => $school->name,
                    'school_code' => $school->school_code,
                    'level' => $school->level,
                    'city' => $school->city,
                    'status' => $school->status,
                    'membership_status' => $school->pivot?->status,
                    'joined_at' => $school->pivot?->joined_at,
                    'roles' => $schoolRoles->map(fn ($role) => [
                        'id' => $role->id,
                        'name' => $role->name,
                        'slug' => $role->slug,
                    ])->values(),
                ];
            })->values(),
        ];
    }
}