<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/13
 * Time: 12:03 PM
 */

namespace App\Process;


use Jobby\ScheduleChecker;
use Swoole\Process;

class SystemJob
{
    private $jobs = [];
    private $config = [];

    private $debugLogFile;

    /**
     * @param $jobId
     * @param array $config
     * @throws \Exception
     */
    public function add($jobId, array $config)
    {
        $config = array_merge($this->config, $config);

        if (empty($config['schedule'])) {
            throw new \Exception("'schedule' is required for '$jobId' job");
        }

        if (!isset($config['exec'])) {
            throw new \Exception("'exec' is required for '$jobId' job");
        }

        $this->jobs[] = [$jobId, $config];
    }

    public function run()
    {
        $scheduleChecker = new ScheduleChecker();
        foreach ($this->jobs as $jobConfig) {
            list($jobId, $config) = $jobConfig;

            $enabled = $config['enabled'] ?? true;
            $output = $config['output'] ?? false;

            if (!$enabled) {
                $this->debugLog("job({$jobId}) is not enabled");
                continue;
            }

            if (!$scheduleChecker->isDue($config['schedule'])) {
                $this->debugLog("job({$jobId}) is not schedule with ({$config['schedule']})");
                continue;
            }

            $process = new Process(function (Process $childProcess) use ($config) {

                $exec = $config['exec'];
                $args = $config['args'] ?? [];
                $childProcess->exec($exec, $args);

            }, true, false);
            $process->start();

            $this->debugLog("job({$jobId}) start with ({$config['schedule']})");

            if ($output && is_writable($output)) {
                file_put_contents($output, $process->read() . PHP_EOL, FILE_APPEND);
            }
        }
        Process::wait();
    }

    /**
     * @param array
     */
    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return mixed
     */
    public function getDebugLogFile()
    {
        return $this->debugLogFile;
    }

    /**
     * @param mixed $debugLogFile
     */
    public function setDebugLogFile($debugLogFile): void
    {
        $this->debugLogFile = $debugLogFile;
    }

    private function debugLog($message)
    {
        if ($this->debugLogFile) {
            if (is_writable($this->debugLogFile) || !file_exists($this->debugLogFile)) {
                $datetime = date('Y-m-d H:i:s');
                file_put_contents($this->debugLogFile, "[{$datetime}] " . $message . PHP_EOL, FILE_APPEND);
            }
        }
    }
}