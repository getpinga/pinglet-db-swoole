<?php

declare(strict_types=1);

namespace Db;

use Swoole\ConnectionPool;
use Db\UniversalConfig;

class UniversalPool {

    public const DEFAULT_SIZE = 64;
    protected $pool = false;

    public function __construct(array $config, int $size = self::DEFAULT_SIZE) {
        $this->pool = new ConnectionPool(function () use($config) {
            $driver = $config['driver'];
			$availableDrivers = ['mysql','pgsql','redis'];

			if (!in_array($driver, $availableDrivers)) {
				return false;
			}
            switch ($driver) {
                case 'mysql': return $this->getMySQLConnection($config);
                case 'pgsql': return $this->getPostgresConnection($config);
                case 'redis': return $this->getRedisConnection($config);
            }
        }, $size);
    }

    public function close() {
        if (!$this->pool) {
            return false;
        }
        return $this->pool->close();
    }

    public function fill() {
        if (!$this->pool) {
            return false;
        }
        return $this->pool->fill();
    }

    public function get() {
        if (!$this->pool) {
            return false;
        }
        return $this->pool->get();
    }

    public function put($connection) {
        if (!$this->pool) {
            return false;
        }
        return $this->pool->put($connection);
    }

    private function getMySQLConnection(&$config) {
        $conn = new \Swoole\Coroutine\Mysql();
        $conn->connect($config);
        return $conn;
    }

    private function getPostgresConnection(&$config) {
        $conn = new \Swoole\Coroutine\PostgreSQL();
        $conn->connect($config);
        return $conn;
    }

    private function getRedisConnection(&$config) {
        $redis = new \Redis();
        $arguments = [$config['host'], $config['port']];
        if ($config['timeout'] !== 0.0) {
            $arguments[] = $config['timeout'];
        }
        if ($config['retry_interval'] !== 0) {
            $arguments[] = null;
            $arguments[] = $config['retry_interval'];
        }
        if ($config['read_timeout'] !== 0.0) {
            $arguments[] = $config['read_timeout'];
        }
        $redis->connect(...$arguments);
        if ($config['auth']) {
            $redis->auth($config['auth']);
        }
        if ($config['dbindex'] !== 0) {
            $redis->select($config['dbindex']);
        }
        return $redis;
    }

}
