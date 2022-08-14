<?php


namespace App\Admin\Controller;

use App\Admin\Service\ExampleService;
use Carbon\Carbon;
use DI\Annotation\Inject;
use Illuminate\Support\Str;
use support\Request;
use support\Response;

class Example
{
    /**
     * @Inject
     * @var ExampleService
     */
    private ExampleService $service;

    public function list(): Response
    {
        return successJson($this->service->getList());
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