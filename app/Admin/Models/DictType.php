<?php

namespace app\Admin\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
class DictType extends CModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_dict_type';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'dict_id';

    protected $guarded = ['dict_id'];

    /**
     * 使用软删除
     */
    use SoftDeletes;

}
