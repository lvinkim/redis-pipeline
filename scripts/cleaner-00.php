<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 4/13/18
 * Time: 10:16 AM
 */

/**
 * 每天 00 点清理
 */

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// load env
$env = new Dotenv(__DIR__ . '/../');
$env->load();

$configs = \App\Utility\ConfigReader::getConfigs();

foreach ($configs as $config) {
    $filepath = $config['filepath'];
    @unlink($filepath);
}