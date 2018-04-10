<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 11/04/2018
 * Time: 12:08 AM
 */

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// load env
$env = new Dotenv(__DIR__ . '/../');
$env->load();

$redisHost = getenv('REDIS_HOST');
$redisPort = getenv('REDIS_PORT');
$redisPassword = getenv('REDIS_PASSWORD');

$parameters = [
    'scheme' => 'tcp',
    'host' => $redisHost,
    'port' => $redisPort,
];
$options = [
    'parameters' => [
        'password' => $redisPassword
    ],
];
$redisClient = new \Predis\Client($parameters, $options);

//每隔2000ms触发一次
$timer = swoole_timer_tick(1000, function ($timer_id) use ($redisClient) {

    $configs = \App\Utility\ConfigReader::getConfigs();

    $phpTailfs = \App\Utility\TailfFactory::genTailfs($configs);
    /** @var \App\Services\PhpTailf $phpTailf */
    foreach ($phpTailfs as $channel => $phpTailf) {
        $newLines = $phpTailf->getNewLines();
        foreach ($newLines as $newLine) {
            $redisClient->publish($channel, $newLine);
        }
    }

});
