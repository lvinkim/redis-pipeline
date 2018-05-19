<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 11/04/2018
 * Time: 12:08 AM
 */

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
$env = new Dotenv(__DIR__ . '/../');
$env->load();

$mainProcess = new \App\Swoole\MainProcess();

$pipelineSubProcess = new \App\Swoole\SubProcess\PipelineSubProcess();

// 添加子进程
$mainProcess->appendSubProcess($pipelineSubProcess);

$mainProcess->run();