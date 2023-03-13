# Pinglet DB for Swoole

Pinglet database pool for Swoole, based on the wonderful [ipejasinovic/swoole-universal-pool](https://github.com/ipejasinovic/swoole-universal-pool). While it is tuned for [Pinglet framework](https://github.com/getpinga/pinglet), it can be used in mostly all Swoole apps.

***Note: not yet for production use as it overloads the database when load is high. Investigating the reasons.***

## How to use

```php
# Example MySQL or PostgreSQL

<?php

require __DIR__ . '/vendor/autoload.php';

$dbConfig = (new \Pinglet\Db\Config)
        ->withDriver('mysql or pgsql')
        ->withHost('127.0.0.1')
        ->withPort(3306)
        ->withDbName('test')
        ->withUsername('root')
        ->withPassword('root')
        ->toArray();

$dbPool = new \Pinglet\Db\Pool($dbConfig, 2);

$conn = $dbPool->get();
$result = $conn->query("select * from test;");
var_dump ($result);
$dbPool->put($conn);
```

```php
# Example Redis

<?php

require __DIR__ . '/vendor/autoload.php';

$dbConfig = (new \Pinglet\Db\Config)
        ->withDriver('redis')
        ->withHost('127.0.0.1')
        ->withPort(6379)
        ->withAuth('admin')
        ->withReadTimeout(-1)
        ->toArray();

$dbPool = new \Pinglet\Db\Pool($dbConfig, 2);

$conn = $dbPool->get();
$conn->set('test', 'test_value');
var_dump($conn->get('test'));
$dbPool->put($conn);
```
