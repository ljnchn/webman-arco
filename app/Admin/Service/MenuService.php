<?php

namespace App\Admin\Service;

use app\Admin\Models\Menu;
use Carbon\Carbon;

class MenuService {

    function getList(): array
    {
        $modelList  = Menu::query()
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
        Menu::find($id);
        return getCamelAttributes(Menu::find($id)->attributesToArray());
    }

    function add($createData): bool
    {
        $createData['create_time'] = Carbon::now();
        return Menu::insert($createData);
    }

    function edit($updateData): bool
    {
        $model       = Menu::find($updateData['menu_id']);
        $model->fill($updateData);
        return $model->save();
    }

    function del($id): ?bool
    {
        return Menu::find($id)->delete();
    }

}