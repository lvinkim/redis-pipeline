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

    public static function makeMap($configs)
    {
        $configsMap = array_combine(
            array_column($configs, 'channel'),
            array_column($configs, 'filepath')
        );
        return $configsMap;
    }

    public static function cleanCache($channel)
    {
        $configs = self::getConfigs();
        $configsMap = ConfigReader::makeMap($configs);

        $filepath = $configsMap[$channel] ?? false;
        
        if (is_file($filepath)) {
            file_put_contents($filepath, '');
        }

    }

}