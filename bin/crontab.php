#!/usr/bin/env php
<?php


require dirname(__DIR__) . '/vendor/autoload.php';

$period = 60 * 1000;    // 每隔 60*1000ms (1分钟) 触发一次
$timer = swoole_timer_tick($period, function ($timer_id) {

    $phpBin = '/usr/bin/env php';
    $console = __DIR__ . '/console.php';
    $logDir = __DIR__ . '/../var/logs/';
    $date = date('Y-m-d');

    try {

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

        // 更多任务 ...

        $jobby->run();

    } catch (\Error $e) {
        null;
    } catch (Exception $e) {
        null;
    } finally {
        null;
    }

});