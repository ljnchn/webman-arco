<?php

namespace App\Admin\Service;

use App\Admin\Model\DictData;
use App\Enums\CacheType;
use App\Enums\Constant;
use support\Cache;

class DictDataService extends BaseService
{
    public function __construct()
    {
        $this->model = new DictData();
    }

    function getDictDataByType($type): array
    {
        $cacheKey = CacheType::DICT() . $type;
        $data     = Cache::get($cacheKey) ?? [];
        if (!$data) {
            $modelList = $this->model
                ->where('dict_type', $type)
                ->where('status', Constant::NORMAL())
                ->orderBy('dict_sort')
                ->get();

            foreach ($modelList as $model) {
                $item            = getCamelAttributes($model->attributesToArray());
                $data[]          = $item;
                $item['default'] = $item['isDefault'] == 'Y';
            }
            Cache::set($cacheKey, $data);
        }
        return $data;
    }
}