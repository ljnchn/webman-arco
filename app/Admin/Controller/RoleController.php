<?php


namespace App\Admin\Controller;

use App\Admin\Service\RoleService;
use App\Enums\HttpCode;
use support\Request;
use support\Response;

class RoleController extends BaseController
{

    public function __construct()
    {
        $this->service = new RoleService();
    }

    public function changeStatus(Request $request): Response
    {
        $roleId = $request->input('roleId');
        $status = $request->input('status');
        if ($this->service->changeStatus($roleId, $status)) {
            return successJson();
        }
        return failJson();
    }

    public function roleMenuTreeselect(Request $request, $id): Response
    {
        return json([
            'code'        => HttpCode::SUCCESS(),
            'msg'         => 'success',
            'checkedKeys' => $this->service->roleMenu($id),
            'menus'       => $this->service->treeSelect()
        ]);
    }

    public function treeSelect(Request $request): Response
    {
        return successJson($this->service->treeSelect());
    }

}