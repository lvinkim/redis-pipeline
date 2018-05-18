<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 13/05/2018
 * Time: 4:34 PM
 */

namespace Tests;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// load env
$env = new Dotenv(__DIR__ . '/../..');
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

$channel = 'tests.log.' . date('Y-m-d', strtotime('+1 day'));
$newLine = json_encode([
    'id' => uniqid(),
    'datetime' => date('Y-m-d H:i:s'),
    'host' => gethostname()
]);
$response = $redisClient->publish($channel, $newLine);
var_dump($response);