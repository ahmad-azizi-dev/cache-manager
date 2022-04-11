<?php

use App\Cache\CacheManager;

require_once('vendor/autoload.php');


try {
    $cacheManager = CacheManager::setCache('redis', 'redis', 6379);
} catch (Exception $e) {
    die($e->getMessage() . PHP_EOL);
}

$cacheManager->set('one', '1');
$cacheManager->lPush('two', '1');
$cacheManager->lPush('two', '2');
echo $cacheManager->get('one') . PHP_EOL;
echo $cacheManager->lPop('two') . PHP_EOL;

try {
    $cacheManager = CacheManager::setCache('memcached');
} catch (Exception $e) {
    die($e->getMessage() . PHP_EOL);
}


$cacheManager->set('one', '1');
$cacheManager->lPush('two', '2'); // now it works instead of generating an exception :)
echo $cacheManager->get('one') . PHP_EOL;
echo $cacheManager->lPop('two') . PHP_EOL;
