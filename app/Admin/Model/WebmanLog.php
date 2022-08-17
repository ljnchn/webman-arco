<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $id (主键)
 * @property string $ip 
 * @property string $method 
 * @property string $url 
 * @property string $params 
 * @property float $exec_time 
 * @property string $exception 
 * @property string $created_time
 */
class WebmanLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'webman_log';

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
