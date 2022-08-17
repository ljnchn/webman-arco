<?php

namespace App\Admin\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use support\Model;

/**
 * @property integer $user_id     用户ID(主键)
 * @property integer $dept_id     部门ID
 * @property string  $user_name   用户账号
 * @property string  $nick_name   用户昵称
 * @property string  $user_type   用户类型（00系统用户）
 * @property string  $email       用户邮箱
 * @property string  $phonenumber 手机号码
 * @property mixed   $sex         用户性别（0男 1女 2未知）
 * @property string  $avatar      头像地址
 * @property string  $password    密码
 * @property mixed   $status      账号状态（0正常 1停用）
 * @property string  $login_ip    最后登录IP
 * @property string  $login_date  最后登录时间
 * @property string  $create_by   创建者
 * @property string  $create_time 创建时间
 * @property string  $update_by   更新者
 * @property string  $update_time 更新时间
 * @property string  $remark      备注
 * @property string  $deleted_at
 */
class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_user';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['*'];

    use SoftDeletes;
}
