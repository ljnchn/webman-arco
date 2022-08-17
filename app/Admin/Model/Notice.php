<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $notice_id      公告ID(主键)
 * @property string  $notice_title   公告标题
 * @property mixed   $notice_type    公告类型（1通知 2公告）
 * @property mixed   $notice_content 公告内容
 * @property mixed   $status         公告状态（0正常 1关闭）
 * @property string  $create_by      创建者
 * @property string  $create_time    创建时间
 * @property string  $update_by      更新者
 * @property string  $update_time    更新时间
 * @property string  $remark         备注
 */
class Notice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_notice';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'notice_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['*'];
}
