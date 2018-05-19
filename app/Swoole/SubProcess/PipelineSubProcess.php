<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 19/05/2018
 * Time: 12:56 PM
 */

namespace App\Swoole\SubProcess;


use App\Swoole\SubProcess;
use App\Utility\ConfigReader;
use App\Utility\TailfFactory;
use Predis\Client;

class PipelineSubProcess implements SubProcess
{
    /**
     * @param $index
     */
    public function run($index)
    {
        $redisHost = getenv('REDIS_HOST');
        $redisPort = getenv('REDIS_PORT');
        $redisPassword = getenv('REDIS_PASSWORD');

        $parameters = [
            'scheme' => 'tcp',
            'host' => $redisHost,
            'port' => $redisPort,
        ];
        $options = [
            'parameters' => [
                'password' => $redisPassword
            ],
        ];
        $redisClient = new Client($parameters, $options);

        //每隔 1000ms 触发一次
        $timer = swoole_timer_tick(1000, function ($timer_id) use ($redisClient) {

            try {
                $configs = ConfigReader::getConfigs();

                $phpTailfs = TailfFactory::genTailfs($configs);
                /** @var \App\Services\PhpTailf $phpTailf */
                foreach ($phpTailfs as $channel => $phpTailf) {
                    $newLines = $phpTailf->getNewLines();
                    foreach ($newLines as $newLine) {
                        $redisClient->publish($channel, $newLine);
                    }
                }
            } catch (\Exception $exception) {
                echo $exception->getMessage() . PHP_EOL;
            }

        });
    }
}