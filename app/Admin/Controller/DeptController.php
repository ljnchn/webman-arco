<?php


namespace App\Admin\Controller;

use App\Admin\Service\DeptService;
use Carbon\Carbon;
use DI\Annotation\Inject;
use Illuminate\Support\Str;
use support\Db;
use support\Request;
use support\Response;

class DeptController
{
    /**
     * @Inject
     * @var deptService
     */
    private DeptService $deptService;

    public function list(): Response
    {
        return successJson($this->deptService->getList());
    }

    public function info(Request $request, $id): Response
    {
        return successJson($this->deptService->getOne($id));
    }

    public function add(Request $request): Response
    {
        $creatData = [];
        foreach ($request->post() as $key => $item) {
            $creatData[Str::snake($key)] = $item;
        }
        $creatData['create_by']   = user()->getInfo()['user']['userName'];
        $creatData['create_time'] = Carbon::now();
        if ($this->deptService->add($creatData)) {
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
        if ($this->deptService->edit($updateData)) {
            return successJson();
        } else {
            return failJson();
        }
    }

    public function del(Request $request, $id): Response
    {
        if ($this->deptService->del($id)) {
            return successJson();
        }
        return failJson();
    }

    public function exclude(Request $request, $id): Response
    {
        return successJson($this->deptService->exclude($id));
    }

}
