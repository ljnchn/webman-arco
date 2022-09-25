<?php

namespace App\Admin\Validate;

use WebmanTech\LaravelValidation\Facades\Validator;

class BaseValidate
{
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [];
    }

    /**
     * 场景值设置
     *
     * @return array
     */
    public function scenes(): array
    {
        return [];
    }

    /**
     * 根据您场景值获取验证规则
     * @param $scene
     * @return array
     */
    protected function getRulesByScene($scene): array
    {
        $rules = $this->rules();
        if (!$scene) {
            return $rules;
        }
        $scenes = $this->scenes();
        if (!isset($scenes[$scene])) {
            return $rules;
        }
        foreach ($rules as $attribute => $rule) {
            if (!in_array($attribute, $scenes[$scene])) {
                unset($rules[$attribute]);
            }
        }
        return $rules;
    }

    public function validate($input, $scene = ''): \Illuminate\Contracts\Validation\Validator
    {
        $rules            = $this->getRulesByScene($scene);
        $messages         = $this->messages();
        $customAttributes = $this->attributes();
        config('translation.locale');
        return Validator::instance()->make($input, $rules, $messages, $customAttributes);
    }


}