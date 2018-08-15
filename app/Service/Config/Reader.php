<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 3:17 PM
 */

namespace App\Service\Config;


use App\Entity\Channel;
use App\Entity\Config;
use App\Entity\Embed\RedisConfig;
use App\Service\Component\ShareableService;
use Psr\Container\ContainerInterface;

class Reader extends ShareableService
{
    private $configFile;
    private $projectDirectory;
    private $inputDirectory;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $settings = $container['settings'];
        $directory = $settings['config']['directory'];
        $this->configFile = $directory . '/pipeline.json';

        $this->projectDirectory = $settings['root']['directory'];
        $this->inputDirectory = $settings['input']['directory'];
    }

    public function readPipelineJson()
    {
        $configs = [];
        if (is_file($this->configFile)) {
            $configs = json_decode(file_get_contents($this->configFile), true);
        }
        return $configs;
    }

    /**
     * 获取当前所有可用配置
     * @return Config[]
     */
    public function getConfigs()
    {
        $configs = $this->readPipelineJson();

        $acceptConfigs = [];
        foreach ($configs as $config) {
            $id = $config['id'] ?? '';
            $channel = $config['channel'] ?? '';
            $filePath = $config['filePath'] ?? '';
            $enable = $config['enable'] ?? false;
            $postfixFormat = $config['postfixFormat'] ?? '';
            $redisConfigs = $config['redis'] ?? [];

            if ($id && $channel && $filePath && $enable && $redisConfigs) {
                $configEntity = new Config();

                $configEntity->setId($id);
                $configEntity->setChannel($channel);
                $configEntity->setFilePath($filePath);
                $configEntity->setEnable($enable);
                $configEntity->setPostfixFormat($postfixFormat);

                $redisConfigEntities = [];
                foreach ($redisConfigs as $redisConfig) {
                    $host = $redisConfig['host'] ?? '';
                    $port = $redisConfig['port'] ?? '6379';
                    $pass = $redisConfig['pass'] ?? '';

                    if (!$host) {
                        continue;
                    }
                    $redisConfigEntity = new RedisConfig();
                    $redisConfigEntity->setHost($host);
                    $redisConfigEntity->setPort($port);
                    $redisConfigEntity->setPass($pass);
                    $redisConfigEntities[] = $redisConfigEntity;
                }
                $configEntity->setRedisConfigs($redisConfigEntities);

                $acceptConfigs[] = $configEntity;
            }
        }

        return $acceptConfigs;
    }

    /**
     * 根据 id 获取配置
     * @param $id
     * @return Config|null
     */
    public function getConfigById($id)
    {
        $acceptConfigs = $this->getConfigs();

        $config = null;
        foreach ($acceptConfigs as $acceptConfig) {

            if ($acceptConfig->getId() === $id) {
                $config = $acceptConfig;
                break;
            }
        }

        return $config;
    }

    /**
     * @param Config $configEntity
     * @param Channel $channelEntity
     * @return mixed|string
     */
    public function genFullPath(Config $configEntity, Channel $channelEntity)
    {
        // 组装要 follow 的文件名
        $filePathPrefix = $configEntity->getFilePath();
        $postfixFormat = $configEntity->getPostfixFormat();

        $date = $channelEntity->getDate();
        if (!$date) {
            $date = date($postfixFormat);
        }

        $fullPath = $filePathPrefix . $date;

        $fullPath = str_replace('PROJECT_DIRECTORY', $this->projectDirectory, $fullPath);
        $fullPath = str_replace('INPUT_DIRECTORY', $this->inputDirectory, $fullPath);

        return $fullPath;
    }
}