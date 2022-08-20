<?php

namespace App\Admin\Service;

use App\Admin\Model\DictData;
use App\Enums\Constant;

class DictDataService
{
    use TraitService;

    public function __construct()
    {
        $this->model = new DictData();
    }

    function getDictDataByType($type): array
    {
        $modelList = $this->model
            ->where('dict_type', $type)
            ->where('status', Constant::NORMAL())
            ->orderBy('dict_sort')
            ->get();

        $data = [];
        foreach ($modelList as $model) {
            $item            = getCamelAttributes($model->attributesToArray());
            $item['default'] = $item['isDefault'] == 'Y';
            $data[]          = $item;
        }
        return $data;
    }
}