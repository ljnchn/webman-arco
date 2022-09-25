<?php

namespace App\Admin\Validate;

class PostValidate extends BaseValidate
{
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'post_code' => 'required|string',
            'post_name' => 'required|string',
            'post_sort' => 'required|integer',
            'status' => 'required|integer',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'post_code' => '岗位编码',
            'post_name' => '岗位名称',
            'remark' => '备注',
        ];
    }

}