<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $dept_id 部门id(主键)
 * @property integer $parent_id 父部门id
 * @property string $ancestors 祖级列表
 * @property string $dept_name 部门名称
 * @property integer $order_num 显示顺序
 * @property string $leader 负责人
 * @property string $phone 联系电话
 * @property string $email 邮箱
 * @property mixed $status 部门状态（0正常 1停用）
 * @property string $create_by 创建者
 * @property string $create_time 创建时间
 * @property string $update_by 更新者
 * @property string $update_time 更新时间
 * @property string $deleted_at 删除标志（0代表存在 2代表删除）
 */
class Dept extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dept';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'dept_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    
}
