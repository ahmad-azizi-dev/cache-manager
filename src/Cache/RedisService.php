<?php

namespace App\Cache;

/**
 *  implementation adapter pattern
 */
class RedisService implements CacheServiceInterface
{
    protected \Redis $cache;

    public function __construct($host, $port)
    {
        $this->cache = new \Redis();
        $this->cache->connect($host, $port);
    }

    public function set(string $key, $value, string $ttl = null): bool
    {
        return $this->cache->set($key, $value, $ttl);
    }

    public function get(string $key)
    {
        return $this->cache->get($key);
    }

    public function lPush(string $key, ...$values)
    {
        return $this->cache->lPush($key, ...$values);
    }

    public function lPop(string $key)
    {
        return $this->cache->lPop($key);
    }

    public function flushAll(): bool
    {
        return $this->cache->flushAll();
    }
}