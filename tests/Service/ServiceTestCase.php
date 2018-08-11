<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 22/07/2018
 * Time: 7:12 PM
 */

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Slim\App;

class ServiceTestCase extends TestCase
{
    /** @var ContainerInterface */
    protected $container;

    /** @var array */
    protected $settings;

    public function setUp()
    {
        $settings = require dirname(__DIR__) . '/../src/settings.php';
        $app = new App($settings);
        require dirname(__DIR__) . '/../src/dependencies.php';
        $this->container = $app->getContainer();

        $this->settings = $this->container->get('settings');
    }
}