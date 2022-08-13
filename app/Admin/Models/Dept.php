<?php

namespace app\Admin\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
class Dept extends CModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_dept';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'dept_id';

    protected $guarded = ['dept_id'];

    /**
     * 使用软删除
     */
    use SoftDeletes;

}
