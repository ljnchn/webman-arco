<?php

namespace App\Admin\Service;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\ArrayShape;
use support\Model;
use think\Validate;

class BaseService
{
    /**
     * @var Model
     */
    public Model $model;

    /**
     * @var Validate|null
     */
    public ?Validate $validate = null;

    /**
     * 根据条件获取分页数量
     * @param int         $pageSize  每页数量
     * @param int         $pageNum   当前页数
     * @param array       $where     筛选条件
     * @param array       $ascOrder  正序字段
     * @param array       $descOrder 倒序字段
     * @param string      $queryDate 查询的时间字段
     * @param string|null $beginTime 开始时间
     * @param string|null $endTime   结束时间
     * @return array
     */
    #[ArrayShape(['rows' => "array", 'total' => "int"])]
    function list(int $pageSize, int $pageNum, array $where = [], array $ascOrder = [], array $descOrder = [], string $queryDate = 'create_time', string $beginTime = null, string $endTime = null): array
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
            $query->whereDate($queryDate, '>=', $beginTime);
        }
        if ($endTime) {
            $query->whereDate($queryDate, '<=', $endTime);
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
     * 查询 builder
     * @return Builder
     */
    function query(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * 新增数据
     * @param $createData
     * @return int
     * @throws Exception
     */
    function add($createData): int
    {
        if ($this->validate) {
            if ($this->validate->hasScene('add')) {
                $this->validate->scene('add');
            }
            if (!$this->validate->check($createData)) {
               throw new Exception(implode(';', $this->validate->getError()));
            }
        }
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
     * @throws Exception
     */
    function edit($updateData): bool
    {
        foreach ($updateData as $key => $v) {
            if (is_array($v)) {
                unset($updateData[$key]);
            }
        }
        if ($this->validate) {
            if ($this->validate->hasScene('edit')) {
                $this->validate->scene('edit');
            }
            if (!$this->validate->check($updateData)) {
                $error = is_array($this->validate->getError()) ? implode(';', $this->validate->getError()) : $this->validate->getError();
                throw new Exception($error);
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

}