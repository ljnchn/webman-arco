<?php


namespace App\Admin\Controller;

use App\Admin\Service\DictTypeService;
use support\Request;
use support\Response;

class DictTypeController extends BaseController
{

    public function __construct()
    {
        $this->service = new DictTypeService();
    }

    public function optionList(Request $request): Response
    {
        $data = $this->service->list(100, 1);
        return successJson($data['rows']);
    }

}
