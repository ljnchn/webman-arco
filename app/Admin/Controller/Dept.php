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
        $deptList = Db::table('sys_dept')->where([
            'status' => 0,
            'del_flag' => 0,
        ])->orderBy('order_num')->orderBy('parent_id')->get();
        $deptData = [];
        foreach ($deptList as $dept) {
            $deptData[] = [
                'ancestors' => $dept->ancestors,
                'children' => [],
                'createBy' => $dept->create_by,
                'createTime' => $dept->create_time,
                'deptId' => $dept->dept_id,
                'deptName' => $dept->dept_name,
                'email' => $dept->email,
                'leader' => $dept->leader,
                'orderNum' => $dept->order_num,
                'parentId' => $dept->parent_id,
                'phone' => $dept->phone,
            ];
        }
        return successJson($deptData);
    }

    public function add(Request $request): Response
    {
        $creatData = [];
        foreach ($request->post() as $key => $item) {
            $creatData[Str::snake($key)] = $item;
        }
        $parent = Db::table('sys_dept')->where('dept_id', $creatData['parent_id'])->first();
        $creatData['ancestors'] = $parent->ancestors . ',' . $creatData['parent_id'];
        $creatData['create_by'] = user()->getInfo()['user']['userName'];
        $creatData['create_time'] = Carbon::now();
        if (Db::table('sys_dept')->insert($creatData)) {
            return successJson();
        } else {
            return failJson();
        }
    }

    public function edit(Request $request, $id)
    {

    }

    public function del(Request $request, $id)
    {

    }

}
