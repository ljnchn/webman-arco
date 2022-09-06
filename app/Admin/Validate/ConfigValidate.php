<?php

namespace App\Admin\Validate;

use think\Validate;

class ConfigValidate extends Validate
{
    protected $rule =   [
        'config_name'  => 'require|max:100',
        'config_key'  => 'require|max:100',
        'config_value'  => 'require|max:500',
        'config_type'  => 'require|max:1',
        'remark'  => 'max:500',
    ];

    protected $message  =   [
        'config_name.require' => '名称必须',
        'config_name.max'     => '名称最多不能超过100个字符',
    ];

    protected $scene = [
        'edit'  =>  ['config_name','config_key'],
    ];

}