#!/usr/bin/env php
<?php

use App\Service\Config\Reader;
use Slim\App;

require dirname(__DIR__) . '/vendor/autoload.php';


$period = 10 * 1000;    // 每隔 10*1000ms (10秒) 触发一次
echo $period . PHP_EOL;
$timer = swoole_timer_tick($period, function ($timerId) {

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
        $systemJob->setConfig(['exec' => $phpBin]);

        $systemJob->add('cmd-trial', [
            'args' => [$console, 'cmd:trial'],
            'schedule' => '* * * * *',
            'output' => $logDir . '/cmd-auto-clean.log.' . $date,
            'enabled' => true,
        ]);

        $systemJob->add('cmd-alive', [
            'args' => [$console, 'cmd:alive'],
            'schedule' => '* * * * *',
            'output' => $logDir . '/cmd-auto-clean.log.' . $date,
            'enabled' => true,
        ]);

        $systemJob->add('cmd-auto-clean', [
            'args' => [$console, 'cmd:auto:clean'],
            'schedule' => '0 * * * *',
            'output' => $logDir . '/cmd-auto-clean.log.' . $date,
            'enabled' => true,
        ]);

        // 更多任务 ...
        foreach ($configs as $config) {

            $channel = $config->getChannel();

            $systemJob->add("cmd-tail-follow-{$channel}", [
                'args' => [$console, 'cmd:tail:follow', "--channel={$channel}"],
                'schedule' => '* * * * *',
                'output' => $logDir . '/cmd-auto-clean.log.' . $date,
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

});