<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $menu_id     菜单ID(主键)
 * @property string  $menu_name   菜单名称
 * @property integer $parent_id   父菜单ID
 * @property integer $order_num   显示顺序
 * @property string  $path        路由地址
 * @property string  $component   组件路径
 * @property string  $query       路由参数
 * @property integer $is_frame    是否为外链（0是 1否）
 * @property integer $is_cache    是否缓存（0缓存 1不缓存）
 * @property mixed   $menu_type   菜单类型（M目录 C菜单 F按钮）
 * @property mixed   $visible     菜单状态（0显示 1隐藏）
 * @property mixed   $status      菜单状态（0正常 1停用）
 * @property string  $perms       权限标识
 * @property string  $icon        菜单图标
 * @property string  $create_by   创建者
 * @property string  $create_time 创建时间
 * @property string  $update_by   更新者
 * @property string  $update_time 更新时间
 * @property string  $remark      备注
 */
class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sys_menu';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'menu_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['*'];

}
