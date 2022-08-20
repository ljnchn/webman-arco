<?php

namespace App\Admin\Service;

use App\Admin\Model\Dept;
use Carbon\Carbon;

class DeptService
{

    use TraitService;

    public function __construct()
    {
        $this->model = new Dept();
    }

    function add($createData): bool
    {
        $parentModel               = $this->model::find($createData['parent_id']);
        $createData['ancestors']   = $parentModel->ancestors . ',' . $createData['parent_id'];
        $createData['create_time'] = Carbon::now();
        return $this->model->insert($createData);
    }

    function edit($updateData): bool
    {
        $id          = $updateData[$this->model->getKeyName()];
        $key         = $this->model->getKeyName();
        $parentModel = $this->model->find($updateData['parent_id']);
        if ($parentModel) {
            $updateData['ancestors'] = $parentModel->ancestors . ',' . $updateData['parent_id'];
        }
        $this->model->newQuery()->where($key, $id)->update($updateData);
        return true;
    }

    /**
     * 根据 ID 获取该职位上面所有职位列表
     * @param $id
     * @return array
     */
    function exclude($id): array
    {
        $model       = $this->model->find($id);
        $parentModel = $this->model->findMany(explode(',', $model->ancestors));
        $returnData  = [];
        foreach ($parentModel as $item) {
            $returnData[] = getCamelAttributes($item->attributesToArray());
        }
        return $returnData;
    }

}