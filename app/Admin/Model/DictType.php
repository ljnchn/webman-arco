<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $dict_id     字典主键(主键)
 * @property string  $dict_name   字典名称
 * @property string  $dict_type   字典类型
 * @property mixed   $status      状态（0正常 1停用）
 * @property string  $create_by   创建者
 * @property string  $create_time 创建时间
 * @property string  $update_by   更新者
 * @property string  $update_time 更新时间
 * @property string  $remark      备注
 */
class DictType extends Model
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

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['*'];
}
