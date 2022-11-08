<?php

namespace App\Admin\Validate;

use think\Validate;

class BaseValidate extends Validate
{

    /**
     * 是否过滤字段
     * @var bool
     */
    protected $filter = false;

    /**
     * 是否批量认证
     * @var bool
     */
    protected $batch = false;

    /**
     * 获取单个错误信息
     * @return string
     */
    function getError(): string
    {
        $error = parent::getError();
        return is_array($error) ? $error[0] : $error;
    }

    /**
     * 获取多个错误信息数组
     * @return array
     */
    function getErrors(): array
    {
        $error = parent::getError();
        return is_array($error) ? $error : [$error];
    }

    /**
     * 根据场景值过滤输入的数据
     * @param array  $data
     * @param string $scene
     * @return array
     */
    function filterData(array $data, string $scene = ''): array
    {
        if (!$this->filter) {
            return $data;
        }
        $fields = $this->scene[$scene] ?? array_keys($this->rule);
        foreach ($data as $k => $v) {
            if (!in_array($k, $fields)) {
                unset($k);
            }
        }
        return $data;
    }

}