<?php

require __DIR__ . '/vendor/autoload.php';

Swoole\Runtime::enableCoroutine();

$dbConfig = (new Db\UniversalConfig)
        ->withDriver('redis')
        ->withHost('127.0.0.1')
        ->withPort(6379)
        ->withAuth('admin')
        ->withReadTimeout(-1);

$dbPool = new Db\UniversalPool($dbConfig, 2);

go(function () use (&$dbPool) {
    $conn = $dbPool->get();
    $conn->set('test', 'test_value');
    var_dump($conn->get('test'));
    $dbPool->put($conn);
});
