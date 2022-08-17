<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $info_id        访问ID(主键)
 * @property string  $user_name      用户账号
 * @property string  $ipaddr         登录IP地址
 * @property string  $login_location 登录地点
 * @property string  $browser        浏览器类型
 * @property string  $os             操作系统
 * @property mixed   $status         登录状态（0成功 1失败）
 * @property string  $msg            提示消息
 * @property string  $login_time     访问时间
 */
class UserLogin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_user_login';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'info_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['*'];
}
