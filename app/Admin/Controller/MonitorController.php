<?php

namespace App\Admin\Controller;

use App\Admin\Model\UserLogin;
use App\Enums\CacheType;
use App\Enums\HttpCode;
use support\Redis;
use support\Request;
use support\Response;

class MonitorController extends BaseController
{
    public function loginInfo(Request $request): Response
    {
        $query = UserLogin::query();
        if ($request->get('status') != null) {
            $query->where('status', $request->get('status'));
        }
        if ($request->get('userName')) {
            $query->where('user_name', $request->get('user_name'));
        }
        $query->orderByDesc('login_time');
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

    /** Redis 信息
     * @param Request $request
     * @return Response
     */
    public function info(Request $request): Response
    {
        $info         = Redis::info();
        $commandStats = [];
        $database     = getenv('REDIS_DATABASE');
        $dbSize       = 0;
        if (isset($info['db' . $database])) {
            $arr    = explode(',', $info['db' . $database]);
            $dbSize = str_replace('keys=', '', $arr[0]);
        }
        foreach (Redis::info('COMMANDSTATS') as $key => $command) {
            $commandStats[] = [
                'name'  => str_replace('cmdstat_', '', $key),
                'value' => str_replace('calls=', '', explode(',', $command)[0])
            ];
        }
        return successJson([
            'dbSize'       => $dbSize,
            'commandStats' => $commandStats,
            'info'         => $info,
        ]);
    }

    /**
     * 查看缓存内容
     * @param Request $request
     * @return Response
     */
    public function list(Request $request): Response
    {
        $cacheTypeList = [];
        foreach (CacheType::options() as $key => $value) {
            $cacheTypeList[] = [
                'remark'    => $key,
                'cacheName' => $value,
            ];
        }
        return successJson($cacheTypeList);
    }

    /**
     * 查看缓存keys
     * @param Request $request
     * @param string  $cacheName
     * @return Response
     */
    public function keys(Request $request, string $cacheName): Response
    {
        return successJson(Redis::keys($cacheName . '*'));
    }

    /**
     * 查看缓存内容
     * @param Request $request
     * @param string  $cacheName
     * @param string  $cacheKey
     * @return Response
     */
    public function view(Request $request, string $cacheName, string $cacheKey): Response
    {
        return successJson([
            'cacheKey'   => $cacheKey,
            'cacheName'  => $cacheName,
            'cacheValue' => Redis::get($cacheKey),
        ]);
    }

    /**
     * 删除一个缓存
     * @param Request $request
     * @param string  $cacheKey
     * @return Response
     */
    public function delete(Request $request, string $cacheKey): Response
    {
        Redis::del($cacheKey);
        return successJson();
    }
}