<?php

namespace app\Admin\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
class Role extends CModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_role';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'role_id';

    protected $guarded = ['role_id'];

}
