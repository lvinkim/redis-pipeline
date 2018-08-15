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

            $id = $config->getId();
            $channelEntity = $this->cacheRedisService->getChannel($id);

            $redisConfigs = [];
            foreach ($config->getRedisConfigs() as $redisConfig) {
                $redisConfigs[] = [
                    'host' => $redisConfig->getHost(),
                    'port' => $redisConfig->getPort(),
                    'pass' => $redisConfig->getPass(),
                ];
            }

            $configInfo = [
                'config.id' => $id,
                'config.channel' => $config->getChannel(),
                'config.filePath' => $config->getFilePath(),
                'config.postfixFormat' => $config->getPostfixFormat(),
                'config.enable' => $config->isEnable(),
                'config.redis' => $redisConfigs,
                'cache.id' => $channelEntity->getId(),
                'cache.channel' => $channelEntity->getChannel(),
                'cache.size' => $channelEntity->getSize(),
                'cache.date' => $channelEntity->getDate(),
                'cache.updateAt' => $channelEntity->getUpdateAt(),
            ];

            $output->writeln(json_encode($configInfo, JSON_PRETTY_PRINT));
        }

        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 结束");
    }
}