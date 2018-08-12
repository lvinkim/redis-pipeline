<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/1
 * Time: 10:41 PM
 */

namespace App\Command;


use App\Service\Redis\PipeRedisService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AliveCommand extends Command
{
    /** @var PipeRedisService */
    private $pipeRedisService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->pipeRedisService = $container[PipeRedisService::class];
    }

    protected function configure()
    {
        $this->setName('cmd:alive')
            ->setDescription('上报心跳');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $nowTime = date('Y-m-d H:i:s');
        $output->writeln("[{$nowTime}] 上报心跳开始");

        $channel = 'app-alive';
        $content = json_encode([
            'host' => getenv('HOST_NICKNAME') ?: gethostname(),
            'item' => 'redis-pipeline',
            'value' => 1,
        ]);
        $result = $this->pipeRedisService->publish($channel, $content);

        $nowTime = date('Y-m-d H:i:s');
        $output->writeln("[{$nowTime}] 上报心跳结束 {$result}");
    }
}