#!/usr/bin/env php
<?php


use App\Service\Config\Reader;
use Slim\App;

require dirname(__DIR__) . '/vendor/autoload.php';

// 初始化 slim app
$settings = require dirname(__DIR__) . '/src/settings.php';
$app = new App($settings);
require dirname(__DIR__) . '/src/dependencies.php';
$container = $app->getContainer();

$period = 10 * 1000;    // 每隔 10*1000ms (10秒) 触发一次
$timer = swoole_timer_tick($period, function ($timerId) use ($container) {

    $phpBin = '/usr/bin/env php';
    $console = __DIR__ . '/console.php';
    $logDir = __DIR__ . '/../var/logs/';
    $date = date('Y-m-d');

    try {

        /** @var Reader $configReader */
        $configReader = $container[Reader::class];

        $configs = $configReader->getConfigs();

        $jobby = new \Jobby\Jobby();

        $jobby->add('cmd-trial', [
            'command' => "{$phpBin} {$console} cmd:trial",
            'schedule' => '* * * * *',
            'output' => $logDir . '/cmd-trial.log.' . $date,
            'enabled' => true,
        ]);

        $jobby->add('cmd-alive', [
            'command' => "{$phpBin} {$console} cmd:alive",
            'schedule' => '* * * * *',
            'output' => $logDir . '/cmd-alive.log.' . $date,
            'enabled' => true,
        ]);

        $jobby->add('cmd-auto-clean', [
            'command' => "{$phpBin} {$console} cmd:auto:clean",
            'schedule' => '0 * * * *',
            'output' => $logDir . '/cmd-auto-clean.log.' . $date,
            'enabled' => true,
        ]);

        // 更多任务 ...
        foreach ($configs as $config) {

            $channel = $config->getChannel();

            $jobby->add("cmd-tail-follow-{$channel}", [
                'command' => "{$phpBin} {$console} cmd:tail:follow --channel={$channel}",
                'schedule' => '* * * * *',
                'output' => "{$logDir}/cmd-tail-follow-{$channel}.log.{$date}",
                'enabled' => true,
            ]);
        }

        $jobby->run();

    } catch (\Error $e) {
        null;
    } catch (Exception $e) {
        null;
    } finally {
        null;
    }

});