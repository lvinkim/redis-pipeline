#!/usr/bin/env php
<?php

use App\Service\Config\Reader;
use Slim\App;

require dirname(__DIR__) . '/vendor/autoload.php';


$period = 15 * 1000;    // 每隔 10*1000ms (10秒) 触发一次

$func = function () {

    // 初始化 slim app
    $settings = require dirname(__DIR__) . '/src/settings.php';
    $app = new App($settings);
    require dirname(__DIR__) . '/src/dependencies.php';
    $container = $app->getContainer();

    $phpBin = '/usr/bin/php';
    $console = __DIR__ . '/console.php';
    $logDir = __DIR__ . '/../var/logs/crontab';
    $date = date('Y-m-d');

    try {

        /** @var Reader $configReader */
        $configReader = $container[Reader::class];
        $configs = $configReader->getConfigs();

        $systemJob = new \App\Process\SystemJob();
        $systemJob->setDebugLogFile($logDir . "/system-job-debug.log." . $date);

        $systemJob->add('cmd-trial', [
            'command' => "{$phpBin} {$console} cmd:trial",
            'schedule' => '* * * * *',
            'enabled' => true,
        ]);

        $systemJob->add('cmd-alive', [
            'command' => "{$phpBin} {$console} cmd:alive",
            'schedule' => '* * * * *',
            'enabled' => true,
        ]);

        $systemJob->add('cmd-auto-clean', [
            'command' => "{$phpBin} {$console} cmd:auto:clean",
            'schedule' => '0 * * * *',
            'enabled' => true,
        ]);

        // 更多任务 ...
        foreach ($configs as $config) {

            $channel = $config->getChannel();

            $systemJob->add("cmd-tail-follow-{$channel}", [
                'command' => "{$phpBin} {$console} cmd:tail:follow --channel={$channel}",
                'schedule' => '* * * * *',
                'enabled' => true,
            ]);
        }

        $systemJob->run();

    } catch (\Error $e) {
        null;
    } catch (Exception $e) {
        null;
    } finally {
        null;
    }

};

$timer = swoole_timer_tick($period, $func);