<?php
// DIC configuration


(function () use ($app) {
    $container = $app->getContainer();

    $autoRegister = new \App\Service\AutoRegister($container);

    $autoRegister->register([
        \App\Service\Logger\CustomLogger::class,
        'errorHandler' => \App\Handler\ErrorHandler::class, // 抛出了未处理的异常
        'phpErrorHandler' => \App\Handler\PhpErrorHandler::class, // PHP 运行时错误 throwable
        'notAllowedHandler' => \App\Handler\NotAllowedHandler::class, // 错误的 Http 请求 Method 405
        'notFoundHandler' => \App\Handler\NotFoundHandler::class, // 请求的路由不存在 404
        \App\Service\Config\Reader::class,
        \App\Service\TailFollow\TailFollowService::class,
        \App\Service\Redis\PipelineRedisManager::class,
    ]);

    $autoRegister->bind((function () use ($container) {
        yield \App\Service\Redis\CacheRedisService::class;
    })());

})();

