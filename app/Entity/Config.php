<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 4:30 PM
 */

namespace App\Entity;


class Config
{
    /**
     * @var string
     */
    private $channel;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $postfixFormat;

    /**
     * @var bool
     */
    private $enable;

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return $this->channel;
    }

    /**
     * @param string $channel
     */
    public function setChannel(string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * @param string $filePath
     */
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }

    /**
     * @return string
     */
    public function getPostfixFormat(): string
    {
        return $this->postfixFormat;
    }

    /**
     * @param string $postfixFormat
     */
    public function setPostfixFormat(string $postfixFormat): void
    {
        $this->postfixFormat = $postfixFormat;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }

}