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
use App\Service\Redis\CacheRedisService;
use App\Service\Redis\PipeRedisService;
use Psr\Container\ContainerInterface;

class TailFollowService extends ShareableService
{
    /** @var PipeRedisService */
    private $pipeRedisService;

    /** @var CacheRedisService */
    private $cacheRedisService;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->pipeRedisService = $container[PipeRedisService::class];
        $this->cacheRedisService = $container[CacheRedisService::class];
    }

    /**
     * @param Config $configEntity
     * @param Channel $channelEntity
     */
    public function follow(Config $configEntity, Channel $channelEntity)
    {
        // 组装要 follow 的文件名
        $channelName = $configEntity->getChannel();
        $filePathPrefix = $configEntity->getFilePath();
        $postfixFormat = $configEntity->getPostfixFormat();

        $date = $channelEntity->getDate();
        $size = $channelEntity->getSize();
        if (!$date) {
            $date = date($postfixFormat);
        }
        if (null === $size) {
            $size = -1;
        }

        $newDate = date($postfixFormat);

        $fullPath = $filePathPrefix . $date;

        // follow
        $phpTailFollow = new PhpTailFollow($fullPath, $size);

        $newLines = $phpTailFollow->getNewLines();

        $lastSize = $size;
        $published = false;
        foreach ($newLines as $lastSize => $newLine) {
            $newLine = rtrim($newLine, "\n");
            if ($newLine) {
                $this->pipeRedisService->publish($channelName, $newLine);
                $published = true;
            }
        }

        // 更新 channel 缓存
        $newChannelEntity = new Channel();
        $newChannelEntity->setChannel($channelName);
        $newChannelEntity->setSize($lastSize);
        $newChannelEntity->setUpdateAt((new \DateTime())->format('Y-m-d H:i:s'));


        $newDateTimestamp = strtotime($newDate);
        $dateTimestamp = strtotime($date);

        if (!$published && $newDateTimestamp > $dateTimestamp) {
            // 如果已经没有新内容，并且时间已过一天，则使用 newDate 缓存
            null;
        } else {
            // 其余情况都仍然发布当前文件的内容
            $newDate = $date;
        }

        $newChannelEntity->setDate($newDate);

        $this->cacheRedisService->setChannel($newChannelEntity);
    }

}