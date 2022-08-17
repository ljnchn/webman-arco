<?php


namespace App\Admin\Service;

use Carbon\Carbon;
use JetBrains\PhpStorm\ArrayShape;
use support\Model;

trait TraitService
{

    /**
     * @var Model
     */
    public Model $model;

    #[ArrayShape(['rows' => "array", 'total' => "int"])]
    function list($pageSize, $pageNum, $where = [], $beginTime = '', $endTime = ''): array
    {
        $model = $this->model;
        if ($where) {
            $model->where($where);
        }
        if ($beginTime) {
            $model->where('update_time', '>=', $beginTime);
        }
        if ($endTime) {
            $model->where('update_time', '<=', $endTime);
        }
        $pagination = $model->paginate($pageSize, ['*'], 'page', $pageNum);
        $rows       = [];
        foreach ($pagination->items() as $model) {
            $rows[] = getCamelAttributes($model->attributesToArray());
        }
        return [
            'rows'  => $rows,
            'total' => $pagination->total(),
        ];
    }

    function one($id): array
    {
        $this->model->find($id);
        return getCamelAttributes($this->model->find($id)->attributesToArray());
    }

    function add($createData): bool
    {
        $createData['create_time'] = Carbon::now();
        return $this->model->insert($createData);
    }

    function edit($updateData): bool
    {
        $model = $this->model->find($updateData[$this->model->getKeyName()]);
        if (!$model) {
            return false;
        }
        unset($updateData[$this->model->getKeyName()]);
        $model->fill($updateData);
        return $model->save();
    }

    function del($id): ?bool
    {
        return $this->model->find($id)->delete();
    }

}