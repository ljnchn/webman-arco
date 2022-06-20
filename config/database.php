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

    'default' => 'mysql',

    'connections' => [

        'mysql' => [

            'driver' => 'mysql',

            'host' => getenv('DB_HOST'),

            'port' => 3306,

            'database' => getenv('DB_DATABASE'),

            'username' => getenv('DB_USERNAME'),

            'password' => getenv('DB_PASSWORD'),

            'unix_socket' => '',

            'charset' => 'utf8',

            'collation' => 'utf8_unicode_ci',

            'prefix' => '',

            'strict' => true,

            'engine' => null,

            'options' => [

                // 1009 => getenv('MYSQL_ATTR_SSL_CA') ?: '/etc/ssl/certs/ca-certificates.crt'

            ]

        ],

    ],

];
