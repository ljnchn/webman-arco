<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static SUCCESS()
 * @method static FAIL()
 * @method static NOTFOUND()
 */
enum HttpCode: int
{
    use InvokableCases;

    case SUCCESS = 200;
    case FAIL = 500;
    case NOTFOUND = 404;

}