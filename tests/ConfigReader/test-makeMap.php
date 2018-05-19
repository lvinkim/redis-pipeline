<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 19/05/2018
 * Time: 1:27 PM
 */

namespace Tests;

require __DIR__ . '/../../vendor/autoload.php';

use App\Utility\ConfigReader;
use Dotenv\Dotenv;

// load env
$env = new Dotenv(__DIR__ . '/../../');
$env->load();

$configs = \App\Utility\ConfigReader::getConfigs();

$configsMap = ConfigReader::makeMap($configs);
var_dump($configsMap);
