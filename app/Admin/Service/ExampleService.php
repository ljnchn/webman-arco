<?php


namespace App\Admin\Service;

use app\Admin\Models\Example;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ExampleService
{

    public string $primaryKey = 'example_id';

    function query(): Builder
    {
        return Example::query();
    }

    function getList(): array
    {
        $modelList  = $this->query()->get();
        $returnData = [];
        foreach ($modelList as $model) {
            $returnData[] = getCamelAttributes($model->attributesToArray());
        }
        return $returnData;
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