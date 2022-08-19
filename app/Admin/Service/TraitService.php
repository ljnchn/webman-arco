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
        $query = $this->model->newQuery();
        if ($where) {
            $query->where($where);
        }
        if ($beginTime) {
            $query->where('update_time', '>=', $beginTime);
        }
        if ($endTime) {
            $query->where('update_time', '<=', $endTime);
        }
        $pagination = $query->paginate($pageSize, ['*'], 'page', $pageNum);
        $rows       = [];
        foreach ($pagination->items() as $item) {
            $rows[] = getCamelAttributes($item->attributesToArray());
        }
        return [
            'rows'  => $rows,
            'total' => $pagination->total(),
        ];
    }

    function one($id): array
    {
        return getCamelAttributes($this->model->newQuery()->find($id)->attributesToArray());
    }

    function add($createData): bool
    {
        $createData['create_time'] = Carbon::now();
        return $this->model->insert($createData);
    }

    function edit($updateData): bool
    {
        $query = $this->model->newQuery();
        $key   = $this->model->getKeyName();
        $id    = $updateData[$this->model->getKeyName()];
        $query->where($key, $id)->update($updateData);
        return true;
    }

    function del($id): ?bool
    {
        return $this->model->find($id)->delete();
    }

}