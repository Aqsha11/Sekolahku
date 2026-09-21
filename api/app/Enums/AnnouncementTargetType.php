<?php

namespace App\Enums;

enum AnnouncementTargetType: string
{
    case ALL = 'ALL';
    case TEACHERS = 'TEACHERS';
    case PARENTS = 'PARENTS';
    case STUDENTS = 'STUDENTS';
    case BY_CLASS = 'CLASS';
}