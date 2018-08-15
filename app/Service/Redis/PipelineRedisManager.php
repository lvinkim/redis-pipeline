<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/15
 * Time: 10:09 PM
 */

namespace App\Service\Redis;


use App\Service\Component\ShareableService;
use Predis\Client;

class PipelineRedisManager extends ShareableService
{
    private static $binds = [];

    /**
     * @param $host
     * @param $port
     * @param $pass
     * @return Client
     */
    public function getClient($host, $port, $pass)
    {
        $clientId = md5("tcp://admin:{$pass}@{$host}:{$port}");
        if (!isset(self::$binds[$clientId])) {
            $parameters = [
                'scheme' => 'tcp',
                'host' => $host,
                'port' => $port,
            ];
            $options = null;
            if ($pass) {
                $options = [
                    'parameters' => [
                        'password' => $pass
                    ],
                ];
            }
            self::$binds[$clientId] = new Client($parameters, $options);
        }
        return self::$binds[$clientId];
    }
}