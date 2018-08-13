<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 3/28/18
 * Time: 1:33 PM
 */

namespace App\Command;

use App\Service\Logger\CustomLogger;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TrialCommand extends Command
{
    /** @var CustomLogger */
    private $logger;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();

        $this->logger = $container[CustomLogger::class];
    }

    protected function configure()
    {
        $this->setName('cmd:trial')
            ->setDescription('测试命令');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 测试命令，开始");

        $this->logger->log('cmd-trial', ['rand' => rand(100, 999)], 'console');

        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 测试命令，结束");
    }

}