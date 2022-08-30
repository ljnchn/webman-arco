<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Options;

/**
 * @method static SYSTEM()
 * @method static CAPTCHA()
 * @method static DICT()
 */
enum CacheType: string
{
    use InvokableCases;
    use Options;

    case SYSTEM = 'system:';
    case CAPTCHA = 'captcha:';
    case DICT = 'dict:';

}