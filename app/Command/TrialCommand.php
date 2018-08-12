<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 3/28/18
 * Time: 1:33 PM
 */

namespace App\Command;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TrialCommand extends Command
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('cmd:trial')
            ->setDescription('测试命令');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 测试命令，开始");

        $output->writeln("[{" . date('Y-m-d H:i:s') . "}] 测试命令，结束");
    }

}