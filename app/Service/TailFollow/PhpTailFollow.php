<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 10/04/2018
 * Time: 11:37 PM
 */

namespace App\Service\TailFollow;

class PhpTailFollow
{
    private $file;
    private $lastFetchedSize;
    private $maxLine;

    public function __construct($file, $lastFetchedSize = -1, $maxLine = 10000)
    {
        $this->file = $file;
        $this->lastFetchedSize = $lastFetchedSize;
        $this->maxLine = $maxLine;
    }

    public function getNewLines()
    {
        if (!is_readable($this->file)) {
            return;
        }

        if ($this->lastFetchedSize == -1) {
            $this->lastFetchedSize = filesize($this->file);
        }

        $fp = fopen($this->file, 'r');
        fseek($fp, $this->lastFetchedSize, SEEK_SET);

        $lineCount = 0;
        while (!feof($fp)) {
            $line = fgets($fp);
            $this->lastFetchedSize = ftell($fp);
            yield $this->lastFetchedSize => $line;
            $lineCount++;
            if ($lineCount >= $this->maxLine) {
                break;
            }
        }
    }
}