<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $user_id 用户ID(主键)
 * @property integer $role_id 角色ID(主键)
 */
class UserRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_user_role';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'role_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['*'];
}
