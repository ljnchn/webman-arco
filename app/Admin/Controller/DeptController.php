<?php


namespace App\Admin\Controller;

use App\Admin\Service\DeptService;
use App\Enums\HttpCode;
use support\Request;
use support\Response;

class DeptController
{
    private DeptService $service;

    use TraitController;

    public function __construct()
    {
        $this->service = new DeptService();
    }

    public function list(Request $request): Response
    {
        $pageSize = $request->pageSize;
        $pageNum  = $request->pageNum;
        $ascOrder = ['order_num', 'parent_id'];
        $list     = $this->service->list($pageSize, $pageNum, [], $ascOrder);
        return json([
            'code'  => HttpCode::SUCCESS(),
            'msg'   => 'success',
            'rows'  => $list['rows'],
            'total' => $list['total']
        ]);
    }

    public function treeSelect(Request $request): Response
    {
        return successJson($this->service->treeSelect());
    }

    public function exclude(Request $request, $id): Response
    {
        return successJson($this->service->exclude($id));
    }

}
