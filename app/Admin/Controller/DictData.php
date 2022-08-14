<?php


namespace App\Admin\Controller;

use App\Admin\Service\DictDataService;
use App\Enums\HttpCode;
use Carbon\Carbon;
use DI\Annotation\Inject;
use Illuminate\Support\Str;
use support\Request;
use support\Response;

class DictData
{
    /**
     * @Inject
     * @var DictDataService
     */
    private DictDataService $service;

    public function getDictDataByType(Request $request, $type): Response
    {
        return successJson($this->service->getDictDataByType($type));
    }

    public function list(Request $request): Response
    {
        $pageSize = $request->pageSize;
        $pageNum = $request->pageNum;
        $dictType = $request->get('dictType');
        $data = $this->service->getList($dictType, $pageSize, $pageNum);
        return json([
            'code' => HttpCode::SUCCESS(),
            'msg' => 'success',
            'rows' => $data['rows'],
            'total' => $data['total']
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
