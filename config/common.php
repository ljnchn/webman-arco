<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

return [
    'trace' => false, # 是否记录追踪日志
    'auth' => [
        'key' => getenv('AUTH_KEY'),
        'alg' => 'sha256',
        'expired' => 86400, // token 有效期，单位秒
    ],
    'throttle' => [
        'enable' => false, # 是否限流
        'second' => 60,
        'limit' => 120,
    ],
];
