<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 12:51 PM
 */

require dirname(__DIR__) . '/vendor/autoload.php';

// Instantiate the app
$settings = require dirname(__DIR__) . '/src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require dirname(__DIR__) . '/src/dependencies.php';

// Register middleware
require dirname(__DIR__) . '/src/middleware.php';

// Register routes
require dirname(__DIR__) . '/src/routes.php';

// Run app
$app->run();
