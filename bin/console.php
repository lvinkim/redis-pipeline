#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;


require_once dirname(__DIR__) . '/vendor/autoload.php';

(new \Dotenv\Dotenv(dirname(__DIR__) . '/'))->load();

$console = new Application("redis pipeline console");

$console->addCommands([
    new \App\Command\TrialCommand(),
    new \App\Command\AliveCommand(),
]);

try {
    $console->run();
} catch (Exception $exception) {
    exit($exception->getMessage() . PHP_EOL);
}
