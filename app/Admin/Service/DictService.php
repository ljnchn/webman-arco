<?php

namespace App\Admin\Service;

use App\Admin\Models\DictData;
use App\Enums\Constant;

class DictService {
    function getDictDataByType($type): array
    {
        $modelList = DictData::query()
            ->where('dict_type', $type)
            ->where('status', Constant::NORMAL())
            ->orderBy('dict_sort')
            ->get();
        $data = [];
        foreach ($modelList as $model) {
            $item = getCamelAttributes($model->attributesToArray());
            $item['default'] = $item['is_default'] == 'Y';
            $data[] = $item;
        }
        return $data;
    }
}