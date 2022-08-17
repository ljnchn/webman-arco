<?php

namespace App\Admin\Service;

use App\Admin\Model\DictData;
use App\Enums\Constant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\ArrayShape;

class DictDataService
{
    public string $primaryKey = 'dict_code';

    function query(): Builder
    {
        return DictData::query();
    }

    #[ArrayShape(['rows' => "array", 'total' => "int"])]
    function getList($dictType, $pageSize, $pageNum): array
    {
        $pagination = $this->query()
            ->where('dict_type', $dictType)
            ->orderBy('dict_sort')
            ->paginate($pageSize, ['*'], 'page', $pageNum);
        $rows       = [];
        foreach ($pagination->items() as $model) {
            $rows[] = getCamelAttributes($model->attributesToArray());
        }
        return [
            'rows'  => $rows,
            'total' => $pagination->total(),
        ];
    }

    function getOne($id): array
    {
        $this->query()->find($id);
        return getCamelAttributes($this->query()->find($id)->attributesToArray());
    }

    function add($createData): bool
    {
        $createData['create_time'] = Carbon::now();
        return $this->query()->insert($createData);
    }

    function edit($updateData): bool
    {
        $model = $this->query()->find($updateData[$this->primaryKey]);
        $model->fill($updateData);
        return $model->save();
    }

    function del($id): ?bool
    {
        return $this->query()->find($id)->delete();
    }

    function getDictDataByType($type): array
    {
        $modelList = $this->query()
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