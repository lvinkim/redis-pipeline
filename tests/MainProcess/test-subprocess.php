<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 19/05/2018
 * Time: 12:36 PM
 */

namespace Tests;

require __DIR__ . '/../../vendor/autoload.php';

use App\Swoole\MainProcess;
use App\Swoole\SubProcess\TestSubProcess;
use Dotenv\Dotenv;

$env = new Dotenv(__DIR__ . '/../../');
$env->load();

$mainProcess = new MainProcess();

$subProcessOne = new TestSubProcess();
$subProcessTwo = new TestSubProcess();
$subProcessThree = new TestSubProcess();

$mainProcess->appendSubProcess($subProcessOne);
$mainProcess->appendSubProcess($subProcessTwo);
$mainProcess->appendSubProcess($subProcessThree);

$mainProcess->run();