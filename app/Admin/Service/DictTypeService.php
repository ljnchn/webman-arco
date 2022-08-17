<?php

namespace App\Admin\Service;

use App\Admin\Model\DictType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class DictTypeService {
    public string $primaryKey = 'dict_id';

    function query(): Builder
    {
        return DictType::query();
    }

    function getList($pageSize, $pageNum): array
    {
        $pagination  = $this->query()->paginate($pageSize, ['*'], 'page', $pageNum);
        $rows = [];
        foreach ($pagination->items() as $model) {
            $rows[] = getCamelAttributes($model->attributesToArray());
        }
        return [
            'rows' => $rows,
            'total' =>  $pagination->total(),
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
}