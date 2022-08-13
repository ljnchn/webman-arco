<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static NORMAL()
 * @method static DISABLED()
 * @method static DELETED()
 */
enum Constant: int
{
    use InvokableCases;

    case NORMAL = 0;
    case DISABLED = 1;
    case DELETED = 2;

}