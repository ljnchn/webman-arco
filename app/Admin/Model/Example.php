<?php

namespace app\Admin\Model;

/**
 *
 */
class Example extends CModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_example';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'example_id';

    protected $guarded = ['example_id'];
}
