<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 4:24 PM
 */

namespace Tests\Service\CacheRedisService;


use App\Entity\Channel;
use App\Service\Redis\CacheRedisService;
use Tests\Service\ServiceTestCase;

class CacheRedisServiceTest extends ServiceTestCase
{

    /** @var CacheRedisService */
    private $cacheRedisService;

    public function setUp()
    {
        parent::setUp();
        $this->cacheRedisService = $this->container[CacheRedisService::class];
    }

    public function test()
    {
        $channel = new Channel();
        $channel->setChannel('health');
        $channel->setSize(0);
        $channel->setDate(date('Y-m-d'));
        $channel->setUpdateAt(date('Y-m-d H:i:s'));

        $this->cacheRedisService->setChannel($channel);

        $cacheChannel = $this->cacheRedisService->getChannel($channel->getChannel());

        $this->assertInstanceOf(Channel::class, $cacheChannel);
    }

}