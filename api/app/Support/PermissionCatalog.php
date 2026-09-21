<?php

namespace App\Support;

use Illuminate\Support\Arr;

class PermissionCatalog
{
    /**
     * Daftar permission {module}.{action} yang tersedia di sistem.
     *
     * @return array<string, array<int, string>>
     */
    public static function modules(): array
    {
        return [
            'students' => ['view', 'create', 'update', 'delete', 'export', 'import'],
            'teachers' => ['view', 'create', 'update', 'delete', 'export'],
            'parents' => ['view', 'create', 'update', 'delete'],
            'classes' => ['view', 'create', 'update', 'delete'],
            'enrollment' => ['view', 'create', 'update', 'delete'],
            'subjects' => ['view', 'create', 'update', 'delete'],
            'rooms' => ['view', 'create', 'update', 'delete'],
            'schedules' => ['view', 'create', 'update', 'delete'],
            'attendance' => ['view', 'create', 'manage', 'export'],
            'lesson' => ['view', 'create', 'update'],
            'violations' => ['view', 'create', 'update', 'delete', 'approve'],
            'counseling' => ['view', 'create', 'update', 'delete'],
            'announcements' => ['view', 'create', 'update', 'delete', 'publish'],
            'notifications' => ['view', 'update', 'manage'],
            'reports' => ['view', 'export', 'generate'],
            'audit' => ['view', 'export'],
            'dashboard' => ['view'],
            'settings' => ['view', 'update', 'manage'],
            'users' => ['view', 'create', 'update', 'delete', 'manage'],
            'roles' => ['view', 'manage', 'assign'],
            'files' => ['view', 'create', 'delete', 'manage'],
            'schools' => ['view', 'create', 'update', 'delete', 'manage'],
            'organizations' => ['view', 'manage'],
            'billing' => ['view', 'manage'],
            'plans' => ['view', 'manage'],
            'subscriptions' => ['view', 'manage'],
            'invoices' => ['view', 'create', 'update', 'manage'],
            'payments' => ['view', 'manage'],
        ];
    }

    public static function all(): array
    {
        return Arr::flatten(
            array_map(
                fn ($module, $actions) => array_map(fn ($action) => "$module.$action", $actions),
                array_keys(self::modules()),
                self::modules(),
            ),
        );
    }

    /**
     * Matriks hak akses per role (role slug => kumpulan "module.action").
     *
     * @return array<string, array<int, string>>
     */
    public static function matrix(): array
    {
        $all = self::all();

        return [
            'super_admin' => $all,
            'platform_admin' => array_values(array_filter($all, function ($p) {
                return str_starts_with($p, 'organizations.')
                    || str_starts_with($p, 'schools.')
                    || str_starts_with($p, 'plans.')
                    || str_starts_with($p, 'subscriptions.')
                    || str_starts_with($p, 'invoices.')
                    || str_starts_with($p, 'payments.')
                    || str_starts_with($p, 'billing.')
                    || str_starts_with($p, 'dashboard.')
                    || str_starts_with($p, 'reports.')
                    || str_starts_with($p, 'audit.')
                    || str_starts_with($p, 'users.')
                    || str_starts_with($p, 'roles.');
            })),
            'organization_admin' => array_values(array_filter($all, function ($p) {
                return str_starts_with($p, 'organizations.')
                    || str_starts_with($p, 'schools.')
                    || str_starts_with($p, 'dashboard.')
                    || str_starts_with($p, 'reports.')
                    || str_starts_with($p, 'users.')
                    || str_starts_with($p, 'settings.')
                    || str_starts_with($p, 'billing.')
                    || str_starts_with($p, 'audit.');
            })),
            'school_admin' => array_values(array_filter($all, function ($p) {
                return ! in_array($p, [
                    'subscriptions.manage',
                    'subscriptions.view',
                    'plans.manage',
                    'plans.view',
                    'payments.manage',
                    'payments.view',
                    'organizations.view',
                    'organizations.manage',
                    'schools.manage',
                ], true);
            })),
            'headmaster' => [
                'dashboard.view',
                'students.view', 'students.export',
                'teachers.view',
                'parents.view',
                'classes.view',
                'enrollment.view',
                'subjects.view',
                'schedules.view',
                'attendance.view', 'attendance.export',
                'violations.view',
                'counseling.view',
                'announcements.view', 'announcements.create', 'announcements.publish',
                'notifications.view',
                'reports.view', 'reports.export',
            ],
            'homeroom_teacher' => [
                'dashboard.view',
                'students.view', 'students.export',
                'classes.view',
                'attendance.view', 'attendance.create', 'attendance.manage', 'attendance.export',
                'lesson.view', 'lesson.create', 'lesson.update',
                'schedules.view',
                'violations.view', 'violations.create',
                'announcements.view',
                'notifications.view',
                'reports.view',
            ],
            'teacher' => [
                'dashboard.view',
                'students.view',
                'attendance.view', 'attendance.create', 'attendance.manage',
                'lesson.view', 'lesson.create', 'lesson.update',
                'schedules.view',
                'announcements.view',
                'notifications.view',
                'reports.view',
            ],
            'counselor' => [
                'dashboard.view',
                'students.view',
                'counseling.view', 'counseling.create', 'counseling.update', 'counseling.delete',
                'violations.view',
                'announcements.view',
                'notifications.view',
            ],
            'staff' => [
                'dashboard.view',
                'students.view', 'students.create', 'students.update',
                'attendance.view', 'attendance.create',
                'schedules.view',
                'announcements.view',
                'notifications.view',
                'reports.view',
            ],
            'student' => [
                'dashboard.view',
                'attendance.view',
                'schedules.view',
                'announcements.view',
                'notifications.view',
            ],
            'parent' => [
                'dashboard.view',
                'attendance.view',
                'schedules.view',
                'announcements.view',
                'notifications.view',
                'students.view',
            ],
        ];
    }
}