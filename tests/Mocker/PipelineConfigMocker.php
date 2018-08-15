<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/15
 * Time: 10:51 PM
 */

namespace Tests\Mocker;


class PipelineConfigMocker
{
    public static function getConfigs()
    {
        $configs = json_decode(file_get_contents(
            __DIR__ . '/../var/mock/pipeline.json'
        ), true);
        return $configs;
    }
}