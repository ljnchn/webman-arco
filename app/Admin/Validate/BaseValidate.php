<?php

namespace App\Admin\Validate;

use think\Validate;

class BaseValidate extends Validate
{

    /**
     * 是否批量认证
     * @var bool
     */
    protected $batch = false;

    function getError(): string
    {
        $error = parent::getError();
        return is_array($error) ? $error[0] : $error;
    }

    function getErrors(): array
    {
        $error = parent::getError();
        return is_array($error) ? $error : [$error];
    }

}