<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/15
 * Time: 11:33 PM
 */

$numbers = (function () {
    foreach (range(1, 10) as $value) {
        yield $value;
    }
})();


foreach ($numbers as $number) {
    echo $number . PHP_EOL;
}

/** @var Generator $numbers */
$numbers->rewind();

foreach ($numbers as $number) {
    echo $number . PHP_EOL;
}
