<?php

namespace App\Enums;

enum ParentRelationship: string
{
    case FATHER = 'FATHER';
    case MOTHER = 'MOTHER';
    case GUARDIAN = 'GUARDIAN';
}