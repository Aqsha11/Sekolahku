<?php

namespace App\Enums;

enum FileVisibility: string
{
    case PRIVATE = 'PRIVATE';
    case PROTECTED = 'PROTECTED';
    case PUBLIC = 'PUBLIC';
}