<?php

namespace App\Admin\Service;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\ArrayShape;
use support\Model;

class BaseService
{
    /**
     * @var Model
     */
    public Model $model;

    /**
     * 根据条件获取分页数量
     * @param int    $pageSize  每页数量
     * @param int    $pageNum   当前页数
     * @param array  $where     筛选条件
     * @param array  $ascOrder  正序字段
     * @param array  $descOrder 倒序字段
     * @param string $beginTime 开始时间
     * @param string $endTime   结束时间
     * @return array
     */
    #[ArrayShape(['rows' => "array", 'total' => "int"])]
    function list(int $pageSize, int $pageNum, array $where = [], array $ascOrder = [], array $descOrder = [], $beginTime = null, $endTime = null): array
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

    /**
     * 根据 ID 获取单条数据
     * @param $id
     * @return array
     */
    function one($id): array
    {
        return getCamelAttributes($this->query()->find($id)->attributesToArray());
    }

    /**
     * 新增数据
     * @param $createData
     * @return int
     */
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

    /**
     * 编辑数据
     * @param $updateData
     * @return bool
     */
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

    /**
     * 删除数据
     * @param $id
     * @return bool|null
     */
    function del($id): ?bool
    {
        return $this->model->find($id)->delete();
    }

    /**
     * 查询 builder
     * @return Builder
     */
    function query(): Builder
    {
        return $this->model->newQuery();
    }

}