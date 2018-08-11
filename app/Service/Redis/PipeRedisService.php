<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 5:08 PM
 */

namespace App\Service\Redis;


use App\Service\Component\ShareableService;
use Predis\Client;
use Psr\Container\ContainerInterface;

class PipeRedisService extends ShareableService
{
    private $client;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $settings = $container['settings']['redis']['pipeline'];
        $host = $settings['host'];
        $port = $settings['port'];
        $pass = $settings['pass'];

        $this->client = $this->initialClient($host, $port, $pass);
    }

    public function publish($channel, $message)
    {
        $this->client->publish($channel, $message);
    }

    /**
     * @param $host
     * @param $port
     * @param $pass
     * @return Client
     */
    private function initialClient($host, $port, $pass)
    {
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

        return new Client($parameters, $options);
    }
}