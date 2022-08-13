<?php


namespace App\Admin\Controller;

use App\Admin\Service\DictService;
use DI\Annotation\Inject;
use support\Request;
use support\Response;

class Dict
{
    /**
     * @Inject
     * @var DictService
     */
    private DictService $service;

    public function getDictDataByType(Request $request, $type): Response
    {
        return successJson($this->service->getDictDataByType($type));
    }

}
