<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 6:31 PM
 */

use Slim\App;

require_once dirname(__DIR__) . '/../vendor/autoload.php';

// 初始化 slim app
$settings = require dirname(__DIR__) . '/../src/settings.php';
$app = new App($settings);
require dirname(__DIR__) . '/../src/dependencies.php';

$container = $app->getContainer();

$tickFunc = function ($timerId) use ($container) {

    $settings = $container->get('settings');

    $rootDirectory = $settings['root']['directory'];

    $filePath = $rootDirectory . '/tests/var/mock/health.log.' . date('Y-m-d');

    $data = json_encode([
        'hostname' => getenv('HOST_NICKNAME') ?: gethostname(),
        'datetime' => date('Y-m-d H:i:s'),
        'app' => 'redis-pipeline',
        'item' => 'rand',
        'value' => rand(1000, 9999),
    ]);

    file_put_contents($filePath, $data . PHP_EOL, FILE_APPEND);

};

//$tickFunc(uniqid());

$period = 1000;
$timer = swoole_timer_tick($period, $tickFunc);