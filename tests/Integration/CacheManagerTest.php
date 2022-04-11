<?php

namespace Integration;

use App\Cache\CacheManager;
use App\Cache\CacheServiceInterface;
use PHPUnit\Framework\TestCase;

class CacheManagerTest extends TestCase
{
    private CacheServiceInterface $cacheManager;

    protected function tearDown(): void
    {
        $this->cacheManager->flushAll();
    }

    public function cacheTypeDataProvider(): array
    {
        return [
            ['redis'],
            ['memcached']
        ];
    }

    /**
     * @test
     * @dataProvider cacheTypeDataProvider
     */
    public function set_and_get($cacheType)
    {
        $this->cacheManager = CacheManager::setCache($cacheType);
        $this->cacheManager = CacheManager::setCache();
        $this->cacheManager->set('one', '1');
        $this->assertEquals(1, $this->cacheManager->get('one'));
        $this->assertEquals(1, $this->cacheManager->get('one'), 'double call does not have same result on get method');
    }

    /**
     * @test
     * @dataProvider cacheTypeDataProvider
     */
    public function get_not_exist_key($cacheType)
    {
        $this->cacheManager = CacheManager::setCache($cacheType);
        $this->assertFalse($this->cacheManager->get('one'));
    }

    /**
     * @test
     * @dataProvider cacheTypeDataProvider
     */
    public function lPop_not_exist_key($cacheType)
    {
        $this->cacheManager = CacheManager::setCache($cacheType);
        $this->assertFalse($this->cacheManager->lPop('one'));
    }

    /**
     * @test
     * @dataProvider cacheTypeDataProvider
     */
    public function lPop_not_work_on_not_list_data($cacheType)
    {
        $this->cacheManager = CacheManager::setCache($cacheType);
        $this->cacheManager->set('one', '1');
        $this->assertFalse($this->cacheManager->lPop('one'));
    }

    /**
     * @test
     * @dataProvider cacheTypeDataProvider
     */
    public function lPush_not_work_on_not_list_data($cacheType)
    {
        $this->cacheManager = CacheManager::setCache($cacheType);
        $this->cacheManager->set('one', '1');
        $this->assertFalse($this->cacheManager->lPush('one'));
    }

    /**
     * @test
     * @dataProvider cacheTypeDataProvider
     */
    public function lPush_and_lPop($cacheType)
    {
        $this->cacheManager = CacheManager::setCache($cacheType);
        $this->cacheManager->lPush('one', '1');
        $this->assertEquals(1, $this->cacheManager->lPop('one'));
    }

    /**
     * @test
     * @dataProvider cacheTypeDataProvider
     */
    public function lPush_append_and_lPop($cacheType)
    {
        $this->cacheManager = CacheManager::setCache($cacheType);
        $this->cacheManager->lPush('one', '1');
        $this->cacheManager->lPush('one', '2');
        $this->assertEquals(2, $this->cacheManager->lPop('one'));
        $this->assertEquals(1, $this->cacheManager->lPop('one'));
        $this->assertFalse($this->cacheManager->lPop('one'));
        $this->assertFalse($this->cacheManager->get('one'));
    }

    /**
     * @test
     * @dataProvider cacheTypeDataProvider
     */
    public function lPush_can_append_multiple_values($cacheType)
    {
        $this->cacheManager = CacheManager::setCache($cacheType);
        $this->cacheManager->lPush('one', '1', '2', '3');
        $this->cacheManager->lPush('one', '4', '5', '6');
        $this->assertEquals(6, $this->cacheManager->lPop('one'));
        $this->assertEquals(5, $this->cacheManager->lPop('one'));
        $this->assertEquals(4, $this->cacheManager->lPop('one'));
        $this->assertEquals(3, $this->cacheManager->lPop('one'));
        $this->assertEquals(2, $this->cacheManager->lPop('one'));
        $this->assertEquals(1, $this->cacheManager->lPop('one'));
    }

    /**
     * @test
     * @dataProvider cacheTypeDataProvider
     */
    public function set_over_write($cacheType)
    {
        $this->cacheManager = CacheManager::setCache($cacheType);
        $this->cacheManager->set('one', '1');
        $this->cacheManager->set('one', '2');
        $this->assertEquals(2, $this->cacheManager->get('one'));
    }


    /**
     * @test
     * @throws \Exception
     */
    public function exception_cache_manager_not_found()
    {
        $this->cacheManager = CacheManager::setCache();
        $this->expectExceptionMessage('Cache Manager Not Found');
        CacheManager::setCache('Random string');
    }
}
