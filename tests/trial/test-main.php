<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 19/05/2018
 * Time: 11:22 AM
 */

namespace Tests;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// load env
$env = new Dotenv(__DIR__ . '/../../');
$env->load();

