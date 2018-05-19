<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 5/17/18
 * Time: 6:31 PM
 */

namespace App\Swoole;


interface SubProcess
{
    /**
     * @param $index
     */
    public function run($index);
}