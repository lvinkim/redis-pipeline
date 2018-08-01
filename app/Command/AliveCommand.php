<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/1
 * Time: 10:41 PM
 */

namespace App\Command;


use Predis\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AliveCommand extends Command
{
    protected function configure()
    {
        $this->setName('cmd:alive')
            ->setDescription('上报心跳');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $nowTime = date('Y-m-d H:i:s');
        $output->writeln("[{$nowTime}] 上报心跳开始");

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

        $channel = 'app-alive';
        $content = json_encode([
            'host' => getenv('HOST_NICKNAME') ?: gethostname(),
            'item' => 'redis-pipeline',
            'value' => 1,
        ]);
        $result = $redisClient->publish($channel, $content);

        $nowTime = date('Y-m-d H:i:s');
        $output->writeln("[{$nowTime}] 上报心跳结束 {$result}");
    }
}