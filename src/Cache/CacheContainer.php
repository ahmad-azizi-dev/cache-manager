<?php

namespace App\Cache;

/**
 *  implementation singleton pattern
 */
final class CacheContainer
{
    private static array $instances = [];

    public static function getInstance(string $className, $host, $port): CacheServiceInterface
    {
        if (!isset(self::$instances[$className.$host.$port])) {
            self::$instances[$className.$host.$port] = new $className($host, $port);
        }
        return self::$instances[$className.$host.$port];
    }
}