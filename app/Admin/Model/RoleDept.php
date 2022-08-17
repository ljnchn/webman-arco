<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $role_id 角色ID(主键)
 * @property integer $dept_id 部门ID(主键)
 */
class RoleDept extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_role_dept';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'dept_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['*'];
}
