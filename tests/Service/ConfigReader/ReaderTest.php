<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 3:24 PM
 */

namespace Tests\Service\ConfigReader;

use App\Entity\Config;
use App\Service\Config\Reader;
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
        $channel = 'health';
        $config = $this->reader->getConfigByChannel($channel);

        $this->assertInstanceOf(Config::class, $config);
    }
}