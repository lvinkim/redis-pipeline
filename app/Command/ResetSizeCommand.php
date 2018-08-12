<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/12
 * Time: 9:39 AM
 */

namespace App\Command;


use App\Service\Config\Reader;
use App\Service\Redis\CacheRedisService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ResetSizeCommand extends Command
{

    /** @var Reader */
    private $configReader;

    /** @var CacheRedisService */
    private $cacheRedisService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->configReader = $container[Reader::class];
        $this->cacheRedisService = $container[CacheRedisService::class];
    }

    protected function configure()
    {
        $this->setName('cmd:reset:size')
            ->addOption('channel', null, InputOption::VALUE_REQUIRED)
            ->setDescription('重置指定 channel 的 size 为 -1');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $channel = $input->getOption('channel');

        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 开始");

        $config = $this->configReader->getConfigByChannel($channel);
        if (!$config) {
            $output->writeln("channel {$channel} 配置不存在");
            return;
        }

        $channelEntity = $this->cacheRedisService->getChannel($channel);

        $channelEntity->setChannel($channel);
        $channelEntity->setSize(-1);
        $channelEntity->setUpdateAt((new \DateTime())->format('Y-m-d H:i:s'));

        $this->cacheRedisService->setChannel($channelEntity);

        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 结束");
    }

}