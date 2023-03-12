<?php

require __DIR__ . '/vendor/autoload.php';

$dbConfig = (new \Db\UniversalConfig)
        ->withDriver('mysql')
        ->withHost('127.0.0.1')
        ->withPort(3306)
        ->withDbName('test')
        ->withUsername('root')
        ->withPassword('root')
        ->toArray();

$dbPool = new \Db\UniversalPool($dbConfig, 2);

$conn = $dbPool->get();
$result = $conn->query("select * from test;");
var_dump ($result);
$dbPool->put($conn);
