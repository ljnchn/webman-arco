<?php

namespace App\Admin\Service;

use App\Admin\Model\DictData;
use App\Enums\CacheType;
use App\Enums\Constant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use JetBrains\PhpStorm\ArrayShape;
use support\Cache;
use support\Model;

class ParentService
{
    use TraitService;

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
        return $this->traitList($pageSize, $pageNum, $where = [], $ascOrder = [], $descOrder = [], $beginTime = null, $endTime = null);
    }

    function one($id): array
    {
        return $this->traitOne($id);
    }

    function add($createData): int
    {
        $createData['create_time'] = Carbon::now();
        return $this->traitAdd($createData);
    }

    function edit($updateData): bool
    {
        return $this->traitEdit($updateData);
    }

    function del($id): ?bool
    {
        return $this->traitDel($id);
    }

    function query(): Builder
    {
        return $this->traitQuery();
    }
}