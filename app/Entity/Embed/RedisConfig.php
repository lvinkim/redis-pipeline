<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/15
 * Time: 10:17 PM
 */

namespace App\Entity\Embed;


class RedisConfig
{
    /** @var string */
    private $host;

    /** @var string */
    private $port;

    /** @var string */
    private $pass;

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @param string $port
     */
    public function setPort(string $port): void
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getPass(): string
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     */
    public function setPass(string $pass): void
    {
        $this->pass = $pass;
    }


}