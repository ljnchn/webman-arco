<?php

namespace App\Admin\Controller;

use App\Enums\HttpCode;
use support\Db;
use support\Request;
use support\Response;

class MonitorController
{
    public function loginInfo(Request $request): Response
    {
        $query = Db::table('sys_user_login');
        if ($request->get('status') != null) {
            $query->where('status', $request->get('status'));
        }
        if ($request->get('userName')) {
            $query->where('user_name', $request->get('user_name'));
        }
        $pagination = $query->paginate($request->pageSize, [
            'info_id as infoId',
            'user_name as userName',
            'login_location as loginLocation',
            'login_time as loginTime',
            'ipaddr',
            'browser',
            'os',
            'status',
            'msg',
        ], 'page', $request->pageNum);

        return json([
            'code'  => HttpCode::SUCCESS(),
            'msg'   => 'success',
            'rows'  => $pagination->items(),
            'total' => $pagination->total()
        ]);
    }
}