<?php


namespace App\Admin\Service;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\ArrayShape;
use support\Model;

trait TraitService
{

    /**
     * @var Model
     */
    public Model $model;

    /**
     * @param       $pageSize
     * @param       $pageNum
     * @param array $where     筛选条件
     * @param array $ascOrder  正序字段
     * @param array $descOrder 倒序字段
     * @param       $beginTime
     * @param       $endTime
     * @return array
     */
    #[ArrayShape(['rows' => "array", 'total' => "int"])]
    function list($pageSize, $pageNum, array $where = [], array $ascOrder = [], array $descOrder = [], $beginTime = null, $endTime = null): array
    {
        $query = $this->model->newQuery();
        if ($where) {
            $query->where($where);
        }
        foreach ($descOrder as $column) {
            $query->orderByDesc($column);
        }
        foreach ($ascOrder as $column) {
            $query->orderBy($column);
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

    function add($createData): int
    {
        foreach ($createData as $key => $v) {
            if (is_array($v)) {
                unset($createData[$key]);
            }
        }
        $createData['create_time'] = Carbon::now();
        return $this->model->insertGetId($createData);
    }

    function edit($updateData): bool
    {
        foreach ($updateData as $key => $v) {
            if (is_array($v)) {
                unset($updateData[$key]);
            }
        }
        $query = $this->model->newQuery();
        $id    = $updateData[$this->model->getKeyName()];
        $query->whereKey($id)->update($updateData);
        return true;
    }

    function del($id): ?bool
    {
        return $this->model->find($id)->delete();
    }

    function query(): Builder
    {
        return $this->model->newQuery();
    }

}