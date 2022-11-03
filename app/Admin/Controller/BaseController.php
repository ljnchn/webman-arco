<?php


namespace App\Admin\Controller;

use App\Admin\Service\BaseService;
use App\Admin\Validate\BaseValidate;
use App\Enums\HttpCode;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use support\Request;
use support\Response;

class BaseController
{

    public BaseService   $service;
    public ?BaseValidate $validate  = NULL;
    public array         $where     = [];
    public array         $ascOrder  = [];
    public array         $descOrder = [];
    /**
     * @var array 通用搜索参数
     */
    public array $commonParam = [];
    /**
     * @var array 不同模型个人性化搜索参数
     */
    public array       $customParam = [];
    public string      $queryDate   = 'create_time';
    public string|null $beginTime   = NULL;
    public string|null $endTime     = NULL;

    public function __construct()
    {
        $this->commonParam = ['status'];
        foreach ($this->commonParam as $param) {
            if (!is_null(request()->get($param))) {
                $this->where[] = [Str::snake($param), request()->get($param)];
            }
        }
        foreach ($this->customParam as $param) {
            if (!is_null(request()->get($param))) {
                $this->where[] = [Str::snake($param), request()->get($param)];
            }
        }
        if ($params = request()->get('params')) {
            if (isset($params['beginTime'])) {
                $this->beginTime = $params['beginTime'];
            }
            if (isset($params['beginTime'])) {
                $this->endTime = $params['endTime'];
            }
        }
        if (request()->get('orderByColumn')) {
            if (request()->get('isAsc') == 'descending') {
                $this->descOrder[] = Str::snake(request()->get('orderByColumn'));
            } else {
                $this->ascOrder[] = Str::snake(request()->get('orderByColumn'));
            }
        }
    }

    /**
     * 不带分页的列表，200上限
     * @param Request $request
     * @return Response
     */
    public function allList(Request $request): Response
    {
        $list = $this->service->list(200, 1, $this->where, $this->ascOrder, $this->descOrder, $this->queryDate, $this->beginTime, $this->endTime);
        return successJson($list['rows']);
    }

    /**
     * 带分页的列表
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        $pageSize = $request->pageSize;
        $pageNum  = $request->pageNum;
        $list     = $this->service->list($pageSize, $pageNum, $this->where, $this->ascOrder, $this->descOrder, $this->queryDate, $this->beginTime, $this->endTime);
        return json([
            'code'  => HttpCode::SUCCESS(),
            'msg'   => 'success',
            'rows'  => $list['rows'],
            'total' => $list['total']
        ]);
    }

    public function one(Request $request, $id): Response
    {
        return json($this->service->one($id));
    }

    /**
     * @throws Exception
     */
    public function add(Request $request): Response
    {
        $creatData = [];
        foreach ($request->post() as $key => $item) {
            $creatData[Str::snake($key)] = $item;
        }
        $creatData['create_by']   = user()->getName();
        $creatData['create_time'] = Carbon::now();
        if ($this->validate) {
            if ($this->validate->scene('add')->check($creatData)) {
                return failJson($this->validate->getError());
            }
        }
        if ($this->service->add($creatData)) {
            return successJson();
        } else {
            return failJson();
        }
    }

    /**
     * @throws Exception
     */
    public function edit(Request $request): Response
    {
        $updateData = [];
        foreach ($request->post() as $key => $item) {
            $updateData[Str::snake($key)] = $item;
        }
        $updateData['update_by']   = user()->getName();
        $updateData['update_time'] = Carbon::now();
        if ($this->validate) {
            if ($this->validate->scene('edit')->check($updateData)) {
                return failJson($this->validate->getError());
            }
        }
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