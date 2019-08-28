<?php

namespace UnderScorer\GraphqlServer\Tests\Cache;

use Exception;
use UnderScorer\Core\Tests\TestCase;
use UnderScorer\GraphqlServer\Cache\InMemoryCache;

/**
 * Class InMemoryCacheTest
 * @package UnderScorer\GraphqlServer\Tests\Cache
 */
final class InMemoryCacheTest extends TestCase
{

    /**
     * @var InMemoryCache
     */
    protected $cache;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->cache = new InMemoryCache();
    }

    /**
     * @covers InMemoryCache::set()
     * @covers InMemoryCache::get()
     */
    public function testSet(): void
    {
        $this->cache->set( 'test', true );

        $this->assertTrue( $this->cache->get( 'test' ) );
    }

    /**
     * @covers InMemoryCache::clear()
     */
    public function testClear(): void
    {
        $this->cache->set( 'test', true );
        $this->cache->clear();

        $this->assertEmpty( $this->cache->get( 'test' ) );
    }

    /**
     * @covers InMemoryCache::setMultiple()
     * @throws Exception
     */
    public function testSetMultiple(): void
    {
        $data = [
            'test'  => true,
            'test1' => false,
            'dog'   => 'cat',
        ];

        $this->cache->setMultiple( $data );

        foreach ( $data as $key => $value ) {
            $this->assertEquals(
                $value,
                $this->cache->get( $key )
            );
        }
    }

    /**
     * @covers InMemoryCache::getMultiple()
     * @throws Exception
     */
    public function testGetMultiple(): void
    {
        $data = [
            'test'  => true,
            'test1' => false,
            'dog'   => 'cat',
        ];

        $this->cache->setMultiple( $data );

        $retrievedData = $this->cache->getMultiple( array_keys( $data ) );

        $this->assertEquals( $data, $retrievedData );
    }

    /**
     * @covers InMemoryCache::deleteMultiple()
     * @throws Exception
     */
    public function testDeleteMultiple(): void
    {
        $data = [
            'test'  => true,
            'dog'   => 'pet',
            'plane' => 'vehicle',
        ];

        $this->cache->setMultiple( $data );
        $this->cache->deleteMultiple( [ 'dog', 'test' ] );

        $this->assertEmpty(
            $this->cache->get( 'dog' )
        );

        $this->assertEmpty(
            $this->cache->get( 'test' )
        );

        $this->assertEquals(
            'vehicle',
            $this->cache->get( 'plane' )
        );
    }

    /**
     * @covers InMemoryCache::delete()
     * @throws Exception
     */
    public function testDelete(): void
    {
        $this->cache->set( 'favorite_pet', 'cat' );
        $this->cache->delete( 'favorite_pet' );

        $this->assertEmpty(
            $this->cache->get( 'favorite_pet' )
        );
    }

    /**
     * @covers InMemoryCache::has()
     * @throws Exception
     */
    public function testHas(): void
    {
        $this->cache->set( 'favorite_pet', 'dog' );

        $this->assertTrue(
            $this->cache->has( 'favorite_pet' )
        );
    }

}
