<?php

require __DIR__ . '/vendor/autoload.php';

$dbConfig = (new Db\UniversalConfig)
        ->withDriver('pgsql')
        ->withHost('127.0.0.1')
        ->withPort(5432)
        ->withDbName('test')
        ->withUsername('admin')
        ->withPassword('admin')
        ->toArray();

$dbPool = new Db\UniversalPool($dbConfig, 2);

$conn = $dbPool->get();
$result = $conn->query("select * from test;");
if ($result) {
    var_dump($conn->fetchAll());
}
$dbPool->put($conn);
