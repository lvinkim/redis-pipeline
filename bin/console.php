#!/usr/bin/env php
<?php

use Slim\App;
use Symfony\Component\Console\Application;


require_once dirname(__DIR__) . '/vendor/autoload.php';

// 初始化 slim app
$settings = require dirname(__DIR__) . '/src/settings.php';
$app = new App($settings);
require dirname(__DIR__) . '/src/dependencies.php';

$console = new Application("redis pipeline console");

$console->addCommands([
    new \App\Command\TrialCommand($app->getContainer()),
    new \App\Command\AliveCommand($app->getContainer()),
    new \App\Command\TailFollowCommand($app->getContainer()),
    new \App\Command\AutoCleanCommand($app->getContainer()),
    new \App\Command\GetConfigsCommand($app->getContainer()),
    new \App\Command\ResetSizeCommand($app->getContainer()),
]);

try {
    $console->run();
} catch (Exception $exception) {
    exit($exception->getMessage() . PHP_EOL);
}
