<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 3:24 PM
 */

namespace Tests\Service\ConfigReader;

use App\Entity\Channel;
use App\Entity\Config;
use App\Service\Config\Reader;
use Tests\Mocker\PipelineConfigMocker;
use Tests\Service\ServiceTestCase;

class ReaderTest extends ServiceTestCase
{
    /** @var Reader */
    private $reader;

    public function setUp()
    {
        parent::setUp();

        $this->reader = $this->container[Reader::class];
    }

    public function testGetConfigs()
    {
        $configs = $this->reader->getConfigs();

        $this->assertTrue(is_array($configs));
    }

    public function testGetConfigByChannel()
    {
        $id = 'host-health-watcher';
        $config = $this->reader->getConfigById($id);

        $this->assertInstanceOf(Config::class, $config);
    }

    public function testGenFullPath()
    {
        $id = 'host-health-watcher';
        $config = $this->reader->getConfigById($id);

        $channelEntity = new Channel();

        $channelEntity->setId($config->getId());
        $channelEntity->setChannel($config->getChannel());
        $channelEntity->setSize(0);
        $channelEntity->setDate('2018-08-15');
        $channelEntity->setUpdateAt((new \DateTime())->format('Y-m-d H:i:s'));

        $fullPath = $this->reader->genFullPath($config, $channelEntity);

        $this->assertTrue(is_string($fullPath));
    }

    public function testGenPostfixFormatFullPath()
    {
        $id = 'pc-inbound-product';
        $config = $this->reader->getConfigById($id);

        $channelEntity = new Channel();

        $channelEntity->setId($config->getId());
        $channelEntity->setChannel($config->getChannel());
        $channelEntity->setSize(0);
        $channelEntity->setDate('2018-09-07');
        $channelEntity->setUpdateAt((new \DateTime())->format('Y-m-d H:i:s'));

        $fullPath = $this->reader->genFullPath($config, $channelEntity);

        $this->assertTrue(is_string($fullPath));
    }
}