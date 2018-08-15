<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 5:37 PM
 */

namespace App\Service\TailFollow;


use App\Entity\Channel;
use App\Entity\Config;
use App\Service\Component\ShareableService;
use App\Service\Config\Reader;
use App\Service\Redis\CacheRedisService;
use App\Service\Redis\PipelineRedisManager;
use Psr\Container\ContainerInterface;

class TailFollowService extends ShareableService
{
    /** @var Reader */
    private $configReader;

    /** @var PipelineRedisManager */
    private $pipelineRedisManager;

    /** @var CacheRedisService */
    private $cacheRedisService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->configReader = $container[Reader::class];
        $this->pipelineRedisManager = $container[PipelineRedisManager::class];
        $this->cacheRedisService = $container[CacheRedisService::class];
    }

    /**
     * @param Config $configEntity
     * @param Channel $channelEntity
     */
    public function follow(Config $configEntity, Channel $channelEntity)
    {
        // 组装要 follow 的文件名
        $fullPath = $this->configReader->genFullPath($configEntity, $channelEntity);

        $id = $configEntity->getId();
        $channelName = $configEntity->getChannel();
        $postfixFormat = $configEntity->getPostfixFormat();
        $redisConfigs = $configEntity->getRedisConfigs();

        $date = $channelEntity->getDate();
        $size = $channelEntity->getSize();
        if (!$date) {
            $date = date($postfixFormat);
        }
        if (null === $size) {
            $size = -1;
        }

        // follow
        $phpTailFollow = new PhpTailFollow($fullPath, $size);

        $newLines = $phpTailFollow->getNewLines();

        $lastSize = $size;
        $hasNewLine = false;
        foreach ($newLines as $lastSize => $newLine) {
            $newLine = rtrim($newLine, "\n");
            if ($newLine) {
                foreach ($redisConfigs as $redisConfig) {
                    $host = $redisConfig->getHost();
                    $port = $redisConfig->getPort();
                    $pass = $redisConfig->getPass();

                    $pipelineClient = $this->pipelineRedisManager->getClient($host, $port, $pass);
                    $pipelineClient->publish($channelName, $newLine);
                }
                $hasNewLine = true;
            }
        }

        // 更新 channel 缓存
        $newChannelEntity = new Channel();
        $newChannelEntity->setId($id);
        $newChannelEntity->setChannel($channelName);
        $newChannelEntity->setUpdateAt((new \DateTime())->format('Y-m-d H:i:s'));

        $newDate = date($postfixFormat);

        $newDateTimestamp = strtotime($newDate);
        $dateTimestamp = strtotime($date);

        if (!$hasNewLine && ($newDateTimestamp > $dateTimestamp)) {
            // 如果已经没有新内容，并且时间已过一天，则使用 newDate 缓存
            $lastSize = 0;
        } else {
            // 其余情况都仍然发布当前文件的内容
            $newDate = $date;
        }

        $newChannelEntity->setDate($newDate);
        $newChannelEntity->setSize($lastSize);

        $this->cacheRedisService->setChannel($newChannelEntity);
    }

}