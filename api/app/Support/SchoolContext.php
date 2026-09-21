<?php

namespace App\Support;

use App\Models\School;

class SchoolContext
{
    protected static ?School $school = null;

    public static function set(School $school): void
    {
        static::$school = $school;
    }

    public static function school(): ?School
    {
        return static::$school;
    }

    public static function schoolId(): ?string
    {
        return static::$school?->id;
    }

    public static function clear(): void
    {
        static::$school = null;
    }
}

if (! function_exists('current_school')) {
    function current_school(): ?School
    {
        return SchoolContext::school();
    }
}

if (! function_exists('current_school_id')) {
    function current_school_id(): ?string
    {
        return SchoolContext::schoolId();
    }
}