<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $config_id    参数主键(主键)
 * @property string  $config_name  参数名称
 * @property string  $config_key   参数键名
 * @property string  $config_value 参数键值
 * @property mixed   $config_type  系统内置（Y是 N否）
 * @property string  $create_by    创建者
 * @property string  $create_time  创建时间
 * @property string  $update_by    更新者
 * @property string  $update_time  更新时间
 * @property string  $remark       备注
 */
class Config extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_config';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'config_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['*'];
}
