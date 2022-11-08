<?php

namespace App\Admin\Validate;

class UserValidate extends BaseValidate
{
    protected $rule =   [
        'dept_id'     => 'integer',
        'user_name'   => 'string',
        'nick_name'   => 'string',
        'user_type'   => 'integer',
        'email'       => 'string',
        'phonenumber' => 'string',
        'sex'         => 'integer',
        'avatar'      => 'string',
        'password'    => 'string',
        'status'      => 'integer',
        'login_ip'    => 'string',
        'login_date'  => 'string',
        'create_by'   => 'integer',
        'create_time' => 'string',
        'update_by'   => 'integer',
        'update_time' => 'string',
        'remark'      => 'string',
    ];

    protected $message  =   [
        'dept_id' => '部门',
    ];

    protected $scene = [
        'edit'  =>  ['config_name','config_key'],
    ];

}