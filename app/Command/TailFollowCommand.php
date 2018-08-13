<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 11:58 AM
 */

namespace App\Command;


use App\Service\Config\Reader;
use App\Service\Logger\CustomLogger;
use App\Service\Redis\CacheRedisService;
use App\Service\TailFollow\TailFollowService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TailFollowCommand extends Command
{

    /** @var Reader */
    private $configReader;

    /** @var CacheRedisService */
    private $cacheRedisService;

    /** @var TailFollowService */
    private $tailFollowService;

    /** @var CustomLogger */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->configReader = $container[Reader::class];
        $this->cacheRedisService = $container[CacheRedisService::class];
        $this->tailFollowService = $container[TailFollowService::class];
        $this->logger = $container[CustomLogger::class];

    }

    protected function configure()
    {
        $this->setName('cmd:tail:follow')
            ->addOption('channel', null, InputOption::VALUE_REQUIRED)
            ->setDescription('tail follow channel >> redis pipeline');
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

        $this->tailFollowService->follow($config, $channelEntity);

        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 结束");

        $this->logger->log('cmd-finished', ['cmd' => $this->getName(),'options'=>$input->getOptions()], 'console');

    }
}