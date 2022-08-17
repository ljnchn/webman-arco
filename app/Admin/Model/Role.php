<?php

namespace App\Admin\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use support\Model;

/**
 * @property integer $role_id             角色ID(主键)
 * @property string  $role_name           角色名称
 * @property string  $role_key            角色权限字符串
 * @property integer $role_sort           显示顺序
 * @property mixed   $data_scope          数据范围（1：全部数据权限 2：自定数据权限 3：本部门数据权限 4：本部门及以下数据权限）
 * @property integer $menu_check_strictly 菜单树选择项是否关联显示
 * @property integer $dept_check_strictly 部门树选择项是否关联显示
 * @property mixed   $status              角色状态（0正常 1停用）
 * @property string  $create_by           创建者
 * @property string  $create_time         创建时间
 * @property string  $update_by           更新者
 * @property string  $update_time         更新时间
 * @property string  $remark              备注
 * @property string  $deleted_at          删除标志（0代表存在 2代表删除）
 */
class Role extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_role';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'role_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['*'];

    use SoftDeletes;
}
