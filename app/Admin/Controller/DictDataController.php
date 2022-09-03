<?php


namespace App\Admin\Controller;

use App\Admin\Service\DictDataService;
use App\Enums\HttpCode;
use support\Request;
use support\Response;

class DictDataController extends BaseController
{

    public function __construct()
    {
        $this->service = new DictDataService();
    }

    public function getDictDataByType(Request $request, $type): Response
    {
        return successJson($this->service->getDictDataByType($type));
    }

    public function list(Request $request): Response
    {
        $pageSize = $request->pageSize;
        $pageNum  = $request->pageNum;
        $dictType = $request->get('dictType');
        $where    = ['dict_type' => $dictType,];
        $ascOrder = ['dict_sort'];
        $data     = $this->service->list($pageSize, $pageNum, $where, $ascOrder);
        return json([
            'code'  => HttpCode::SUCCESS(),
            'msg'   => 'success',
            'rows'  => $data['rows'],
            'total' => $data['total']
        ]);
    }

}
