<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $post_id     岗位ID(主键)
 * @property string  $post_code   岗位编码
 * @property string  $post_name   岗位名称
 * @property integer $post_sort   显示顺序
 * @property mixed   $status      状态（0正常 1停用）
 * @property string  $create_by   创建者
 * @property string  $create_time 创建时间
 * @property string  $update_by   更新者
 * @property string  $update_time 更新时间
 * @property string  $remark      备注
 */
class Post extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_post';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'post_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['*'];
}
