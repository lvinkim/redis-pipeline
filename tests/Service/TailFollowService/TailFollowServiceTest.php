<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 6:04 PM
 */

namespace Tests\Service\TailFollowService;


use App\Entity\Channel;
use App\Entity\Config;
use App\Service\TailFollow\TailFollowService;
use Tests\Service\ServiceTestCase;

class TailFollowServiceTest extends ServiceTestCase
{
    /** @var TailFollowService */
    private $tailFollowService;


    public function setUp()
    {
        parent::setUp();
        $this->tailFollowService = $this->container[TailFollowService::class];
    }

    public function testFollow()
    {
        $channelName = 'health';

        $configEntity = new Config();
        $configEntity->setChannel($channelName);
        $configEntity->setPostfixFormat('Y-m-d');
        $configEntity->setEnable(true);
        $filePath = $this->settings['root']['directory'] . '/tests/var/mock/health.log.';
        $configEntity->setFilePath($filePath);

        $channelEntity = new Channel();
        $channelEntity->setChannel($channelName);
        $channelEntity->setSize(330);

        $this->tailFollowService->follow($configEntity, $channelEntity);

        $this->assertTrue(true);
    }
}