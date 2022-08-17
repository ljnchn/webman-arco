<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property integer $pid
 * @property string  $type
 * @property string  $connection
 * @property string  $command
 * @property float   $exec_time
 */
class WebmanLogItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'webman_log_item';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


}
