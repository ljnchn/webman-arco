<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $dict_code 字典编码(主键)
 * @property integer $dict_sort 字典排序
 * @property string $dict_label 字典标签
 * @property string $dict_value 字典键值
 * @property string $dict_type 字典类型
 * @property string $css_class 样式属性（其他样式扩展）
 * @property string $list_class 表格回显样式
 * @property mixed $is_default 是否默认（Y是 N否）
 * @property mixed $status 状态（0正常 1停用）
 * @property string $create_by 创建者
 * @property string $create_time 创建时间
 * @property string $update_by 更新者
 * @property string $update_time 更新时间
 * @property string $remark 备注
 */
class DictData extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dict_data';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'dict_code';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    
}
