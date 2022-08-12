<?php


namespace App\Admin\Controller;

use Carbon\Carbon;
use Illuminate\Support\Str;
use support\Db;
use support\Request;
use support\Response;

class Menu
{
    public function list(Request $request): Response
    {
        $modelList  = Db::table('sys_menu')->orderBy('order_num')->orderBy('parent_id')->get();
        $returnData = [];
        foreach ($modelList as $model) {
            $returnData[] = $this->processModel($model);
        }
        return successJson($returnData);
    }

    public function info(Request $request, $id): Response|array
    {
        $model = Db::table('sys_menu')->where('menu_id', $id)->first();
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
        $creatData['create_by']   = user()->getInfo()['user']['userName'];
        $creatData['create_time'] = Carbon::now();
        if (Db::table('sys_menu')->insert($creatData)) {
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
        $query = Db::table('sys_menu')->where('menu_id', $updateData['menu_id']);
        $model = $query->first();
        if (!$model) {
            return failJson();
        }
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
        $res = Db::table('sys_menu')->where('menu_id', $id)->delete();
        if ($res) {
            return successJson();
        }
        return failJson();
    }

    public function processModel($model): array
    {
        $returnData = [];
        foreach (get_object_vars($model) as $k => $v) {
            $returnData[Str::camel($k)] = $v;
        }
        return $returnData;
    }

}
