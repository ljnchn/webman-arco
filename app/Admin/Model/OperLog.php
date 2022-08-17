<?php

namespace App\Admin\Model;

use support\Model;

/**
 * @property integer $oper_id 日志主键(主键)
 * @property string $title 模块标题
 * @property integer $business_type 业务类型（0其它 1新增 2修改 3删除）
 * @property string $method 方法名称
 * @property string $request_method 请求方式
 * @property integer $operator_type 操作类别（0其它 1后台用户 2手机端用户）
 * @property string $oper_name 操作人员
 * @property string $dept_name 部门名称
 * @property string $oper_url 请求URL
 * @property string $oper_ip 主机地址
 * @property string $oper_location 操作地点
 * @property string $oper_param 请求参数
 * @property string $json_result 返回参数
 * @property integer $status 操作状态（0正常 1异常）
 * @property string $error_msg 错误消息
 * @property string $oper_time 操作时间
 */
class OperLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'oper_log';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'oper_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    
}
