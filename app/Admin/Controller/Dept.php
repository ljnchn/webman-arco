<?php


namespace App\Admin\Controller;

use Carbon\Carbon;
use Illuminate\Support\Str;
use support\Db;
use support\Request;
use support\Response;

class Dept
{
    public function list(Request $request): Response
    {
        $modelList  = Db::table('sys_dept')->where([
            'del_flag' => 0,
        ])->orderBy('order_num')->orderBy('parent_id')->get();
        $returnData = [];
        foreach ($modelList as $model) {
            $returnData[] = $this->processModel($model);
        }
        return successJson($returnData);
    }

    public function info(Request $request, $id): Response|array
    {
        $model = Db::table('sys_dept')->where('dept_id', $id)->first();
        if (!$model) {
            return failJson();
        }
        return successJson($this->processModel($model));
    }

    public function add(Request $request): Response
    {
        $creatData = [];
        foreach ($request->post() as $key => $item) {
            $creatData[Str::snake($key)] = $item;
        }
        $parent                   = Db::table('sys_dept')->where('dept_id', $creatData['parent_id'])->first();
        $creatData['ancestors']   = $parent->ancestors . ',' . $creatData['parent_id'];
        $creatData['create_by']   = user()->getInfo()['user']['userName'];
        $creatData['create_time'] = Carbon::now();
        if (Db::table('sys_dept')->insert($creatData)) {
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
        $query = Db::table('sys_dept')->where('dept_id', $updateData['dept_id']);
        $model = $query->first();
        if (!$model) {
            return failJson();
        }
        $parent = Db::table('sys_dept')->where('dept_id', $updateData['parent_id'])->first();
        $updateData['ancestors']   = $parent->ancestors . ',' . $updateData['parent_id'];
        $updateData['update_by']   = user()->getInfo()['user']['userName'];
        $updateData['update_time'] = Carbon::now();
        if ($query->update($updateData)) {
            return successJson();
        } else {
            return failJson();
        }
    }

    public function del(Request $request, $id): Response
    {
        $res = Db::table('sys_dept')->where('dept_id', $id)->update(['del_flag' => 1]);
        if ($res) {
            return successJson();
        }
        return failJson();
    }

    public function exclude(Request $request, $id): Response
    {
        $model = Db::table('sys_dept')->where('dept_id', $id)->first();
        if (!$model) {
            return failJson();
        }
        $modelList  = Db::table('sys_dept')
            ->where([
                'status'   => 0,
                'del_flag' => 0,
            ])
            ->whereIn('dept_id', explode(',', $model->ancestors))
            ->orderBy('order_num')
            ->orderBy('parent_id')
            ->get();
        $returnData = [];
        foreach ($modelList as $model) {
            $returnData[] = $this->processModel($model);
        }
        return successJson($returnData);
    }


    public function processModel($model): array
    {
        return [
            'ancestors'  => $model->ancestors,
            'createBy'   => $model->create_by,
            'createTime' => $model->create_time,
            'deptId'     => $model->dept_id,
            'deptName'   => $model->dept_name,
            'email'      => $model->email,
            'leader'     => $model->leader,
            'orderNum'   => $model->order_num,
            'parentId'   => $model->parent_id,
            'phone'      => $model->phone,
            'status'     => $model->status,
        ];
    }

}
