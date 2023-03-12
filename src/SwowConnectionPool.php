<?php

namespace Db;

class ConnectionPool {
    private $pool = [];
    private $config;
    private $size;

    public function __construct($callback, $size) {
        $this->config = $callback;
        $this->size = $size;

        for ($i = 0; $i < $this->size; $i++) {
            $connection = $this->createConnection();
            if ($connection) {
                $this->pool[] = $connection;
            } else {
                throw new Exception("Failed to create connection");
            }
        }
    }

    public function getConnection() {
        if (count($this->pool) == 0) {
            $connection = $this->createConnection();
        } else {
            $connection = array_pop($this->pool);
        }

        return $connection;
    }

    public function releaseConnection($connection) {
        if (count($this->pool) < $this->size) {
            $this->pool[] = $connection;
        }
    }

    private function createConnection() {
        $callback = $this->config;
        $connection = $callback();

        return $connection;
    }
}
