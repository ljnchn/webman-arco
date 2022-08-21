<?php
namespace App\Queue\Redis;

use App\Admin\Model\UserLogin;
use Webman\RedisQueue\Consumer;

class Ip2region implements Consumer
{
    // 要消费的队列名
    public string $queue = 'ip2region';

    // 连接名，对应 plugin/webman/redis-queue/redis.php 里的连接`
    public string $connection = 'default';

    // 消费
    public function consume($data)
    {
        $type = $data['type'];
        $id = $data['id'];
        if ($type == 'userLogin') {
            $model = UserLogin::find($id);
            if ($model && $model->ipaddr) {
                $region = ip2region($model->ipaddr);
                if ($region) {
                    $model->login_location = $region;
                    $model->save();
                }
            }
        }
    }
}