<?php


namespace App\Admin\Controller;

use App\Admin\Service\DeptService;
use support\Request;
use support\Response;

class DeptController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->service  = new DeptService();
        $this->ascOrder = ['order_num', 'parent_id'];
        $deptName       = request()->get('deptName');
        if ($deptName) {
            $this->where[] = ['dept_name', 'like', '%' . $deptName . '%'];
        }
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
