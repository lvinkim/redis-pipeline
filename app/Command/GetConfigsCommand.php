<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/12
 * Time: 1:02 AM
 */

namespace App\Command;


use App\Service\Config\Reader;
use App\Service\Redis\CacheRedisService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetConfigsCommand extends Command
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
        $this->setName('cmd:get:configs')
            ->setDescription('读取配置信息');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 开始");

        $configs = $this->configReader->getConfigs();
        foreach ($configs as $config) {

            $channel = $config->getChannel();

            $channelEntity = $this->cacheRedisService->getChannel($channel);

            $configInfo = [
                'config.channel' => $channel,
                'config.FilePath' => $config->getFilePath(),
                'config.PostfixFormat' => $config->getPostfixFormat(),
                'cache.channel' => $channelEntity->getChannel(),
                'cache.Size' => $channelEntity->getSize(),
                'cache.Date' => $channelEntity->getDate(),
                'cache.UpdateAt' => $channelEntity->getUpdateAt(),
            ];

            $output->writeln(json_encode($configInfo, JSON_PRETTY_PRINT));
        }

        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 结束");
    }
}