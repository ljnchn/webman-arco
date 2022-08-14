<?php


namespace App\Admin\Controller;

use App\Admin\Service\DictTypeService;
use App\Enums\HttpCode;
use Carbon\Carbon;
use DI\Annotation\Inject;
use Illuminate\Support\Str;
use support\Request;
use support\Response;

class DictType
{
    /**
     * @Inject
     * @var DictTypeService
     */
    private DictTypeService $service;

    public function optionList(Request $request): Response
    {
        $data = $this->service->getList(100, 1);
        return successJson($data['rows']);
    }

    public function list(Request $request): Response
    {
        $pageSize = $request->pageSize;
        $pageNum = $request->pageNum;
        return json([
            'code' => HttpCode::SUCCESS(),
            'msg' => 'success',
            'rows' => $this->service->getList($pageSize, $pageNum)['rows'],
            'total' => $this->service->getList($pageSize, $pageNum)['total']
        ]);
    }

    public function info(Request $request, $id): Response
    {
        return successJson($this->service->getOne($id));
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
