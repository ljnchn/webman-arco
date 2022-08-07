<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static SUCCESS()
 * @method static FAIL()
 * @method static NOTFOUND()
 * @method static NO_ACCESS()
 * @method static EXPIRED()
 */
enum HttpCode: int
{
    use InvokableCases;

    case SUCCESS = 200;
    case FAIL = 500;
    case NOTFOUND = 404;
    case NO_ACCESS = 403;
    case EXPIRED = 401;

}