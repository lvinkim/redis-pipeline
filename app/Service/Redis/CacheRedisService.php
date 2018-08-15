<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 2:27 PM
 */

namespace App\Service\Redis;


use App\Entity\Channel;
use App\Service\Component\ShareableService;
use Predis\Client;
use Psr\Container\ContainerInterface;

class CacheRedisService extends ShareableService
{
    private $client;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $settings = $container['settings']['redis']['cache'];
        $host = $settings['host'];
        $port = $settings['port'];
        $pass = $settings['pass'];

        $this->client = $this->initialClient($host, $port, $pass);
    }

    /**
     * @param $id
     * @return string
     */
    public function genChannelKey($id)
    {
        return 'channel.' . $id;
    }

    /**
     * @param Channel $channelEntity
     */
    public function setChannel(Channel $channelEntity)
    {
        $key = $this->genChannelKey($channelEntity->getId());

        $this->client->hset($key, 'id', $channelEntity->getId());
        $this->client->hset($key, 'channel', $channelEntity->getChannel());
        $this->client->hset($key, 'size', $channelEntity->getSize());
        $this->client->hset($key, 'date', $channelEntity->getDate());
        $this->client->hset($key, 'updateAt', $channelEntity->getUpdateAt());
    }

    /**
     * @param $id
     * @return Channel
     */
    public function getChannel($id)
    {
        $channelEntity = new Channel();

        $key = $this->genChannelKey($id);

        $channelEntity->setId(strval($this->client->hget($key, 'id')));
        $channelEntity->setChannel(strval($this->client->hget($key, 'channel')));
        $channelEntity->setSize($this->client->hget($key, 'size'));
        $channelEntity->setDate(strval($this->client->hget($key, 'date')));
        $channelEntity->setUpdateAt(strval($this->client->hget($key, 'updateAt')));

        return $channelEntity;
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