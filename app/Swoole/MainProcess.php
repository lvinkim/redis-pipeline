<?php
/**
 * Created by PhpStorm.
 * User: vinkim
 * Date: 5/17/18
 * Time: 5:23 PM
 */

namespace App\Swoole;

/**
 * 主进程守护
 * Class MainProcess
 * @package App\Swoole
 */
class MainProcess
{
    /**
     * 主进程 ID
     * @var int
     */
    private $masterPid = 0;

    /**
     * 进程名
     * @var string
     */
    private $processName;

    /**
     * 子进程实体对象
     * @var array
     */
    private $subProcesses = [];

    /**
     * 所有子进程 ID
     * @var array
     */
    private $works = [];

    public function __construct($processName = 'php-ps')
    {
        $this->processName = $processName;
    }

    public function appendSubProcess(SubProcess $subProcess)
    {
        $this->subProcesses[] = $subProcess;
    }

    /**
     * @param $index
     * @return SubProcess
     */
    private function getSubProcess($index)
    {
        return $this->subProcesses[$index];
    }

    public function run()
    {
        try {
            $mainProcessName = "{$this->processName}:master";
            swoole_set_process_name($mainProcessName); // 设置主进程的进程名
            $this->masterPid = posix_getpid();  // 记录主进程 ID

            $this->console("start main process: {$mainProcessName}, pid: {$this->masterPid}");

            $maxPrecess = count($this->subProcesses);

            // 创建子进程
            for ($i = 0; $i < $maxPrecess; $i++) {
                $this->createProcess($i);
            }

            $this->processWait();   // 主进程等待子进程结束
        } catch (\Exception $e) {
            die('main process error: ' . $e->getMessage());
        }
    }

    private function createProcess($index = null)
    {
        $process = new \swoole_process(function (\swoole_process $worker) use ($index) {

            $mainProcessName = "{$this->processName}:worker-{$index}";
            swoole_set_process_name($mainProcessName);
            $workerPid = posix_getpid();

            $this->console("start worker: {$mainProcessName}, pid: {$workerPid}");

            $subProcess = $this->getSubProcess($index);

            if (is_callable([$subProcess, 'run'])) {
                $subProcess->run($index); // 运行子进程逻辑
            }

            // 检查主进程是否还在，$signo=0，可以检测进程是否存在
            if (!\swoole_process::kill($this->masterPid, 0)) {
                $worker->exit(0);
            }

        }, false, false);

        $pid = $process->start();
        $this->works[$index] = $pid;

        return $pid;
    }

    private function processWait()
    {
        while (1) {
            if (count($this->works)) {
                // 阻塞等待子进程结束
                // 任意一个子进程结束都会向主进程发送 SIGCHLD 信号
                $result = \swoole_process::wait();
                $pid = $result['pid'] ?? false;
                // 示例: $result = array('code' => 0, 'pid' => 15001, 'signal' => 15);
                if ($pid) {
                    $this->rebootProcess($pid);
                }
            } else {
                break;
            }
        }
    }

    private function rebootProcess($pid)
    {
        $index = array_search($pid, $this->works);

        if ($index !== false) {
            $index = intval($index);
            $newPid = $this->createProcess($index);
            $this->console("reboot process[{$index}]: {$pid} -> {$newPid}");
            return;
        }

        $this->console("reboot process error : no pid");
    }

    private function console($message)
    {
        echo $message . PHP_EOL;
    }

}