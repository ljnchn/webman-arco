<?php

namespace App\Admin\Service;

use App\Admin\Model\Dept;
use Carbon\Carbon;

class DeptService
{

    function getList(): array
    {
        $modelList  = Dept::query()
            ->orderBy('order_num')
            ->orderBy('parent_id')
            ->get();
        $returnData = [];
        foreach ($modelList as $model) {
            $returnData[] = getCamelAttributes($model->attributesToArray());
        }
        return $returnData;
    }

    function getOne($id): array
    {
        Dept::find($id);
        return getCamelAttributes(Dept::find($id)->attributesToArray());
    }

    function add($createData): bool
    {
        $parentModel               = Dept::find($createData['parent_id']);
        $createData['ancestors']   = $parentModel->ancestors . ',' . $createData['parent_id'];
        $createData['create_time'] = Carbon::now();
        return Dept::insert($createData);
    }

    function edit($updateData): bool
    {
        $model       = Dept::find($updateData['dept_id']);
        $parentModel = Dept::find($updateData['parent_id']);
        $model->fill($updateData);
        $model->ancestors = $parentModel->ancestors . ',' . $updateData['parent_id'];
        return $model->save();
    }

    function del($id): ?bool
    {
        return Dept::find($id)->delete();
    }

    /**
     * 根据 ID 获取该职位上面所有职位列表
     * @param $id
     * @return array
     */
    function exclude($id): array
    {
        $model       = Dept::find($id);
        $parentModel = Dept::findMany(explode(',', $model->ancestors));
        $returnData  = [];
        foreach ($parentModel as $item) {
            $returnData[] = getCamelAttributes($item->attributesToArray());
        }
        return $returnData;
    }

}