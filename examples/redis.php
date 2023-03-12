<?php

require __DIR__ . '/vendor/autoload.php';

$dbConfig = (new \Pinglet\DB\Config)
        ->withDriver('redis')
        ->withHost('127.0.0.1')
        ->withPort(6379)
        ->withAuth('admin')
        ->withReadTimeout(-1)
        ->toArray();

$dbPool = new \Pinglet\DB\Pool($dbConfig, 2);

$conn = $dbPool->get();
$conn->set('test', 'test_value');
var_dump($conn->get('test'));
$dbPool->put($conn);
