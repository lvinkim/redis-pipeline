<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 4:13 PM
 */

namespace App\Entity;


class Channel
{
    /**
     * @var string
     */
    private $channel;

    /**
     * @var int
     */
    private $size;

    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $updateAt;

    /**
     * @return string
     */
    public function getChannel(): string
    {
        return strval($this->channel);
    }

    /**
     * @param string $channel
     */
    public function setChannel($channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return int|null
     */
    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size): void
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return strval($this->date);
    }

    /**
     * @param string $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getUpdateAt(): string
    {
        return strval($this->updateAt);
    }

    /**
     * @param string $updateAt
     */
    public function setUpdateAt($updateAt): void
    {
        $this->updateAt = $updateAt;
    }


}