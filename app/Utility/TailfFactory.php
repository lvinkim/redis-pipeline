<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 11/04/2018
 * Time: 12:49 AM
 */

namespace App\Utility;


use App\Services\PhpTailf;

class TailfFactory
{
    static private $instances = [];

    public static function genTailfs($configs)
    {
        $phpTailfs = [];
        foreach ($configs as $config) {
            $channel = $config['channel'] ?? '';
            $filepath = $config['filepath'] ?? '';

            if (!isset(self::$instances[$channel])) {
                self::$instances[$channel] = new PhpTailf($filepath);
            }

            $phpTailfs[$channel] = self::$instances[$channel];
        }
        return $phpTailfs;
    }
}