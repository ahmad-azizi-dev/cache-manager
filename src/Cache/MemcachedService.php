<?php

namespace App\Cache;

/**
 *  implementation adapter pattern
 */
class MemcachedService implements CacheServiceInterface
{
    protected \Memcached $cache;

    public function __construct($host, $port)
    {
        $this->cache = new \Memcached();
        $this->cache->addServer($host, $port);
    }

    public function set(string $key, $value, string $ttl = null): bool
    {
        if (is_null($ttl)) {
            $ttl = 0;
        }
        return $this->cache->set($key, $value, $ttl);
    }

    public function get(string $key)
    {
        $value = $this->cache->get($key);
        if ($value instanceof ListDataType) {
            return false;
        }
        return $value;
    }

    /**
     * Adds the values to the head (left) of the list.
     * Creates the list if the key didn't exist.
     * If the key exists and is not a list, FALSE is returned.
     *
     * @return int|false The new length of the list in case of success, FALSE in case of Failure
     *
     * @example
     *
     * $redis->lPush('l', 'v1', 'v2', 'v3', 'v4')   // int(4)
     */
    public function lPush(string $key, ...$values)
    {
        $list = $this->cache->get($key);
        if ($list == false) {
            $list = new ListDataType(array_reverse([...$values]));
            $this->cache->set($key, $list);
            return count($list);
        }
        if ($list instanceof ListDataType) {
            $list->lPush(array_reverse([...$values]));
            $this->cache->set($key, $list);
            return count($list);
        }
        return false;
    }

    /**
     * Returns and removes the first element of the list.
     *
     * @return  mixed|bool if command executed successfully BOOL FALSE in case of failure (empty list)
     *
     * @example
     * <pre>
     * $redis->rPush('key1', 'A');
     * $redis->rPush('key1', 'B');
     * $redis->rPush('key1', 'C');  // key1 => [ 'A', 'B', 'C' ]
     * $redis->lPop('key1');        // key1 => [ 'B', 'C' ]
     * </pre>
     */
    public function lPop(string $key)
    {
        $list = $this->cache->get($key);

        if ($list instanceof ListDataType) {
            $outPut = $list->lPop();
            $this->cache->set($key, $list);
            return $outPut;
        }
        return false;
    }

    public function flushAll(): bool
    {
        return $this->cache->flush();
    }
}