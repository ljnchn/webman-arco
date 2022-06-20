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
    'default' => [
        'host' => getenv('REDIS_HOST'),
        'password' => getenv('REDIS_PASSWORD'),
        'port' => getenv('REDIS_PORT'),
        'database' => getenv('REDIS_DATABASE'),
    ],
    'remote' => [
        'host' => 'apn1-teaching-kangaroo-32604.upstash.io',
        'password' => '4d8a9e265fbd4e5aa3c445245c9a8178',
        'port' => 32604,
        'database' => 0,
    ],
];
