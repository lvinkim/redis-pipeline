<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 19/05/2018
 * Time: 12:32 PM
 */

namespace App\Swoole\SubProcess;

use App\Swoole\SubProcess;

class TestSubProcess implements SubProcess
{
    public function run($index)
    {
        for ($i = 0; $i < 10; $i++) {
            $rand = rand(1, 5);
            echo "send: worker({$index}), say($i), sleep($rand)" . PHP_EOL;
            sleep($rand);
        }
    }
}