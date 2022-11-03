<?php

namespace App\Admin\Validate;

class PostValidate extends BaseValidate
{
    protected $rule = [
        'post_code' => 'require|max:100',
        'post_name' => 'require|max:100',
        'post_sort' => 'require|max:500',
        'status'    => 'require|max:1',
    ];

    protected $message = [
        'post_code.require' => '名称必须',
        'post_name.max'     => '名称最多不能超过100个字符',
    ];

}