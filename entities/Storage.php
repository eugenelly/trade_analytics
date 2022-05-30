<?php

class Storage
{
    private static object $instance;
    private object $signature;
    private static string $storageType = 'Redis';

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();

            self::createSignature();
        }

        return self::$instance;
    }

    public function setSignature($signature)
    {
        $this->signature = $signature;
    }

    public static function createSignature()
    {
        if (self::$storageType === 'Redis') {
            self::$instance->setSignature(new Redis());
        }

        if (self::$storageType === 'Memcached') {
            self::$instance->setSignature(new Memcache());
        }
    }

    public function connect()
    {
        $this->signature->connect('127.0.0.1') or die('Нет соединения');
    }

    public function close()
    {
        $this->signature->close();
    }

    public function get($key): string
    {
        return $this->signature->get($key);
    }

    public function set($key, $value, $timeout = 3600, $flag = false)
    {
        $signature = $this->signature;
        switch (self::$storageType) {
            case 'Redis':
                $signature->set($key, $value, $timeout);
                break;
            case 'Memcache':
                $signature->set($key, $value, $flag, $timeout);
                break;
        }
    }

    public function delete($key): bool
    {
        $signature = $this->signature;

        if (!$this->get($key)) {
            return false;
        }

        switch (self::$storageType) {
            case 'Redis':
                $signature->del($key);
                break;
            case 'Memcache':
                $signature->delete($key);
                break;
        }
        return true;
    }

    public function list(): array
    {
        $list = [];
        foreach ($this->signature->keys('*') as $key) {
            $list[$key] = $this->get($key);
        }

        return $list;
    }
}
