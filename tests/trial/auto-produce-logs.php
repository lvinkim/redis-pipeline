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

    $cursor = time();

    $settings = $container->get('settings');

    $rootDirectory = $settings['root']['directory'];
    $filePath = $rootDirectory . '/var/logstash/health-watcher.log.' . date('Y-m-d');
    $data = json_encode([
        'host' => getenv('HOST_NICKNAME') ?: gethostname(),
        'app' => 'server',
        'item' => 'disk_use',
        'value' => $cursor,
    ]);
    file_put_contents($filePath, $data . PHP_EOL, FILE_APPEND);

    $inputDirectory = $settings['input']['directory'];
    $inputPath = $inputDirectory . '/var/logstash/app-item.log.' . date('Y-m-d');
    $data = json_encode([
        'host' => getenv('HOST_NICKNAME') ?: gethostname(),
        'app' => 'app',
        'item' => 'test',
        'value' => $cursor,
    ]);
    file_put_contents($inputPath, $data . PHP_EOL, FILE_APPEND);

};

//$tickFunc(uniqid());

$period = 1000;
$timer = swoole_timer_tick($period, $tickFunc);