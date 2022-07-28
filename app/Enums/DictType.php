<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static COMMON_STATUS()
 */
enum DictType: string
{
    use InvokableCases;

    case COMMON_STATUS = 'sys_common_status';

}