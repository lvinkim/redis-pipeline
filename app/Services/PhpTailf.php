<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 10/04/2018
 * Time: 11:37 PM
 */

namespace App\Services;

class PhpTailf
{
    private $file;

    private $maxSizeToLoad;

    private $lastFetchedSize;

    public function __construct($file, $maxSizeToLoad = 2097152)
    {
        $this->file = $file;
        $this->maxSizeToLoad = $maxSizeToLoad;

        if (file_exists($this->file)) {
            $this->lastFetchedSize = filesize($this->file);
        } else {
            $this->lastFetchedSize = 0;
        }
    }

    public function getNewLines()
    {
        clearstatcache();

        if (!file_exists($this->file)) {
            $this->lastFetchedSize = 0;
            return [];
        }

        $fsize = filesize($this->file);

        if ($fsize < $this->lastFetchedSize) {
            $this->lastFetchedSize = $fsize;
        }

        $maxLength = ($fsize - $this->lastFetchedSize);

        if ($maxLength > $this->maxSizeToLoad) {
            $maxLength = ($this->maxSizeToLoad / 2);
        }

        $data = [];
        if ($maxLength > 0) {
            $fp = fopen($this->file, 'r');
            fseek($fp, -$maxLength, SEEK_END);
            $data = explode(PHP_EOL, fread($fp, $maxLength));
            $this->lastFetchedSize += $maxLength;
        }

        if (end($data) == "") {
            array_pop($data);
        }

        return $data;
    }
}