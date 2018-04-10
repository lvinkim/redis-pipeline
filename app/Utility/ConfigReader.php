<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 11/04/2018
 * Time: 12:48 AM
 */

namespace App\Utility;

class ConfigReader
{
    public static function getConfigs()
    {
        $configFile = __DIR__ . '/../../config/pipeline.json';
        if (file_exists($configFile)) {
            $configs = json_decode(file_get_contents($configFile), true);
        }
        if (!$configs) {
            $configs = [];
        }

        $acceptConfigs = [];
        foreach ($configs as $config) {
            $channel = $config['channel'] ?? '';
            $filepath = $config['filepath'] ?? '';

            if ($channel && $filepath) {
                $acceptConfigs[] = [
                    'channel' => $channel,
                    'filepath' => $filepath
                ];
            }
        }

        return $acceptConfigs;
    }

}