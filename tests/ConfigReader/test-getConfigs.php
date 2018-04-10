<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 11/04/2018
 * Time: 1:06 AM
 */

namespace Tests;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// load env
$env = new Dotenv(__DIR__ . '/../../');
$env->load();

$configs = \App\Utility\ConfigReader::getConfigs();

var_dump($configs);