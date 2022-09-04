<?php


namespace App\Admin\Controller;

use App\Admin\Service\DictDataService;
use support\Request;
use support\Response;

class DictDataController extends BaseController
{

    public function __construct()
    {
        $this->service     = new DictDataService();
        $this->customParam = ['dictType', 'dictName'];

        parent::__construct();
        $this->ascOrder = ['dict_sort'];
    }

    public function getDictDataByType(Request $request, $type): Response
    {
        return successJson($this->service->getDictDataByType($type));
    }

}
