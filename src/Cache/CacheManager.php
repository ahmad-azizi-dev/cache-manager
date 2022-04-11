<?php

namespace App\Cache;

/**
 *  implementation strategy pattern
 */

class CacheManager
{

    private static array $caches = [
        'redis' => RedisService::class,
        'memcached' => MemcachedService::class,
    ];

    /**
     * @throws \Exception
     */
    public static function setCache(string $type = null, $host = null, $port = null): CacheServiceInterface
    {
        $conf = (include(__DIR__ . '/../config/config.php'))['cache'];
        if (is_null($type)) {
            $type = $conf['default'];
        }

        if (!array_key_exists($type, self::$caches)) {
            throw new \Exception("Cache Manager Not Found");
        }

        if (is_null($host)) {
            $host = $conf[$type]['host'];
        }

        if (is_null($port)) {
            $port = $conf[$type]['port'];
        }
        return CacheContainer::getInstance(self::$caches[$type], $host, $port);
    }

}