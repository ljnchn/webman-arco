<?php


namespace App\Admin\Controller;

use App\Admin\Service\BaseService;
use App\Enums\HttpCode;
use Carbon\Carbon;
use Illuminate\Support\Str;
use support\Request;
use support\Response;

class BaseController
{

    public BaseService $service;
    public array $where = [];
    public array $ascOrder = [];
    public array $descOrder = [];
    public string|null $beginTime = null;
    public string|null $endTime = null;

    /**
     * 不带分页的列表，200上限
     * @param Request $request
     * @return Response
     */
    public function allList(Request $request): Response
    {
        $list = $this->service->list(200, 1, $this->where, $this->ascOrder, $this->descOrder, $this->beginTime, $this->endTime);
        return successJson($list['rows']);
    }

    /**
     * 带分页的列表
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        $pageSize = $request->pageSize;
        $pageNum  = $request->pageNum;
        $list     = $this->service->list($pageSize, $pageNum, $this->where, $this->ascOrder, $this->descOrder, $this->beginTime, $this->endTime);
        return json([
            'code'  => HttpCode::SUCCESS(),
            'msg'   => 'success',
            'rows'  => $list['rows'],
            'total' => $list['total']
        ]);
    }

    public function one(Request $request, $id): Response
    {
        return successJson($this->service->one($id));
    }

    public function add(Request $request): Response
    {
        $creatData = [];
        foreach ($request->post() as $key => $item) {
            $creatData[Str::snake($key)] = $item;
        }
        $creatData['create_by']   = user()->getInfo()['user']['userName'];
        $creatData['create_time'] = Carbon::now();
        if ($this->service->add($creatData)) {
            return successJson();
        } else {
            return failJson();
        }
    }

    public function edit(Request $request): Response
    {
        $updateData = [];
        foreach ($request->post() as $key => $item) {
            $updateData[Str::snake($key)] = $item;
        }
        $updateData['update_by']   = user()->getInfo()['user']['userName'];
        $updateData['update_time'] = Carbon::now();
        if ($this->service->edit($updateData)) {
            return successJson();
        } else {
            return failJson();
        }
    }

    public function del(Request $request, $id): Response
    {
        if ($this->service->del($id)) {
            return successJson();
        }
        return failJson();
    }

}