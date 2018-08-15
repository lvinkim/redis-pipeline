<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/1
 * Time: 10:41 PM
 */

namespace App\Command;


use App\Entity\Config;
use App\Service\Config\Reader;
use App\Service\Logger\CustomLogger;
use App\Service\Redis\PipelineRedisManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AliveCommand extends Command
{
    /** @var Reader */
    private $configReader;

    /** @var PipelineRedisManager */
    private $pipelineRedisManager;

    /** @var CustomLogger */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->configReader = $container[Reader::class];
        $this->pipelineRedisManager = $container[PipelineRedisManager::class];
        $this->logger = $container[CustomLogger::class];
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

        $config = $this->configReader->getConfigById('app-alive');

        if ($config instanceof Config) {

            $channel = $config->getChannel();
            $content = json_encode([
                'host' => getenv('HOST_NICKNAME') ?: gethostname(),
                'item' => 'redis-pipeline',
                'value' => 1,
            ]);

            $redisConfigs = $config->getRedisConfigs();
            foreach ($redisConfigs as $redisConfig) {
                $host = $redisConfig->getHost();
                $port = $redisConfig->getPort();
                $pass = $redisConfig->getPass();

                $pipelineClient = $this->pipelineRedisManager->getClient($host, $port, $pass);
                $result = $pipelineClient->publish($channel, $content);

                $nowTime = date('Y-m-d H:i:s');
                $output->writeln("[{$nowTime}] 上报心跳成功 {$result}");
            }
        }

        $this->logger->log('cmd-finished', ['cmd' => $this->getName(), 'options' => $input->getOptions()], 'console');
    }
}