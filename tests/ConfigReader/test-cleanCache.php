<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 19/05/2018
 * Time: 1:36 PM
 */

namespace Tests;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// load env
$env = new Dotenv(__DIR__ . '/../../');
$env->load();

$channel = 'health-watcher';

\App\Utility\ConfigReader::cleanCache($channel);
