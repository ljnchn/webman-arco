<?php

namespace App\Middleware;

use Carbon\Carbon;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Redis\Events\CommandExecuted;
use support\Db;
use support\Redis;
use Webman\Http\Request;
use Webman\Http\Response;
use Webman\MiddlewareInterface;

class TraceLog implements MiddlewareInterface
{
    private array $items = [];

    /**
     * @param Request $request
     * @param callable $handler
     * @return Response
     */
    public function process(Request $request, callable $handler): Response
    {
        $traceConfig = config('common.trace');
        if (!$traceConfig || !$traceConfig['request']) {
            return $handler($request);
        }
        static $initialized;
        $this->items = [];
        $startTime   = microtime(true);
        $ip          = $request->getRealIp();
        $method      = $request->method();
        $url         = trim($request->fullUrl(), '/');

        if (!$initialized) {
            // 监听 mysql
            if ($traceConfig['mysql'] && class_exists(QueryExecuted::class)) {
                try {
                    Db::listen(function (QueryExecuted $query) {
                        $sql = trim($query->sql);
                        if (strtolower($sql) === 'select 1') {
                            return;
                        }
                        $sql = str_replace("?", "%s", $sql);
                        foreach ($query->bindings as $i => $binding) {
                            if ($binding instanceof \DateTime) {
                                $query->bindings[$i] = $binding->format("'Y-m-d H:i:s'");
                            } else {
                                if (is_string($binding)) {
                                    $query->bindings[$i] = "'$binding'";
                                }
                            }
                        }
                        $log           = vsprintf($sql, $query->bindings);
                        $this->items[] = [
                            'type'       => 'mysql',
                            'connection' => $query->connectionName,
                            'command'    => $log,
                            'exec_time'  => round($query->time, 2),
                        ];
                    });
                } catch (\Throwable $e) {
                    echo $e;
                }
            }
            // 监听 redis
            if ($traceConfig['redis'] && class_exists(CommandExecuted::class)) {
                foreach (config('redis', []) as $key => $config) {
                    if (str_contains($key, 'redis-queue')) {
                        continue;
                    }
                    try {
                        Redis::connection($key)->listen(function (CommandExecuted $command) {
                            foreach ($command->parameters as &$item) {
                                if (is_array($item)) {
                                    $item = implode('\', \'', $item);
                                }
                            }
                            $this->items[] = [
                                'type'       => 'redis',
                                'connection' => $command->connectionName,
                                'command'    => "$command->command('" . implode('\', \'', $command->parameters) . "')",
                                'exec_time'  => round($command->time, 2),
                            ];
                        });
                    } catch (\Throwable $e) {
                    }
                }
            }
            $initialized = true;
        }

        $response = $handler($request);
        $params   = null;
        if ($request->method() === 'POST') {
            $params = json_encode($request->post());
        }
        $execTime  = substr((microtime(true) - $startTime) * 1000, 0, 7);
        $exception = '';
        if (method_exists($response, 'exception')) {
            $exception = $response->exception();
        }
        // 保存日志
        $id = Db::table('webman_log')->insertGetId([
            'ip'           => $ip,
            'method'       => $method,
            'url'          => $url,
            'params'       => $params,
            'exec_time'    => round($execTime, 2),
            'exception'    => $exception,
            'created_time' => Carbon::now(),
        ]);
        if ($id && count($this->items) > 0) {
            foreach ($this->items as $key => $item) {
                $this->items[$key]['pid'] = $id;
            }
            Db::table('webman_log_item')->insert($this->items);
        }

        return $response;
    }
}
