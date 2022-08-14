<?php

namespace app\Admin\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
class DictData extends CModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_dict_data';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'dict_code';

    protected $guarded = ['dict_code'];

}
