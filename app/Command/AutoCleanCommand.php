<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/12
 * Time: 12:51 AM
 */

namespace App\Command;


use App\Service\Config\Reader;
use App\Service\Redis\CacheRedisService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AutoCleanCommand extends Command
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
        $this->setName('cmd:auto:clean')
            ->setDescription('每小时自动清除一次上一天的日志');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 开始");

        $configs = $this->configReader->getConfigs();
        foreach ($configs as $config) {
            $filePathPrefix = $config->getFilePath();
            $postfixFormat = $config->getPostfixFormat();

            $date = date($postfixFormat, strtotime('-1 day'));

            $fullPath = $filePathPrefix . $date;

            $channel = $this->cacheRedisService->getChannel($config->getChannel());

            if (is_writable($fullPath) && $date != $channel->getDate()) {
                file_put_contents($fullPath, '');

                $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 清除 {$fullPath} 成功");
            }
        }

        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 结束");
    }

}