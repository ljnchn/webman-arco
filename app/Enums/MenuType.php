<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static FOLDER()
 * @method static MENU()
 * @method static BUTTON()
 * @method static STATUS_NORMAL()
 * @method static STATUS_DISABLED()
 */
enum MenuType: string
{
    use InvokableCases;

    case FOLDER = 'M'; // 目录
    case MENU = 'C'; // 菜单
    case BUTTON = 'F'; // 按钮

    case STATUS_NORMAL = '0';
    case STATUS_DISABLED = '1';

}