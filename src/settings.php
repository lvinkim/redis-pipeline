<?php

return (function () {

    date_default_timezone_set('Asia/Chongqing');

    (new Dotenv\Dotenv(dirname(__DIR__) . '/'))->load();

    'prod' === getenv('ENV') ? error_reporting(0) : null;

    return [
        'settings' => [
            'env' => getenv('ENV'),
            'displayErrorDetails' => getenv('ENV') == 'dev' ? true : false, // set to false in production
            'addContentLengthHeader' => false, // Allow the web server to send the content-length header
            'root' => [
                'directory' => dirname(__DIR__),
            ],
            'cache' => [
                'directory' => dirname(__DIR__) . '/var/cache',
            ],
            'config' => [
                'directory' => dirname(__DIR__) . '/config',
            ],
            'logger' => [
                'directory' => dirname(__DIR__) . '/var/logs',
            ],
            'redis' => [
                'cache' => [
                    'host' => getenv('CACHE_REDIS_HOST'),
                    'port' => getenv('CACHE_REDIS_PORT'),
                    'pass' => getenv('CACHE_REDIS_PASS'),
                ],
                'pipeline' => [
                    'host' => getenv('REDIS_HOST'),
                    'port' => getenv('REDIS_PORT'),
                    'pass' => getenv('REDIS_PASS'),
                ],
            ],
        ],
    ];

})();
