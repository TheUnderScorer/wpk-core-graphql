<?php

namespace UnderScorer\GraphqlServer\Tests\Data;

use UnderScorer\Core\Tests\TestCase;
use UnderScorer\GraphqlServer\Data\DataLoader;

/**
 * Class DataLoaderTest
 * @package UnderScorer\GraphqlServer\Tests\Data
 */
final class DataLoaderTest extends TestCase
{

    /**
     * @var DataLoader
     */
    protected $loader;

    /**
     * @var array
     */
    protected $data = [
        1 => [
            'id'    => 1,
            'value' => 'Value one!',
        ],
        2 => [
            'id'    => 2,
            'value' => 'Value two!',
        ],
        3 => [
            'id'    => 3,
            'value' => 'Value three!',
        ],
    ];

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loader = new DataLoader( function ( $keys ) {
            $result = [];

            foreach ( $keys as $key ) {
                $result[] = $this->data[ $key ];
            }

            return $result;
        } );
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->loader->flush();
    }

    /**
     * @return void
     */
    public function testLoadMany(): void
    {
        $results = $this->loader->loadMany( [ 1, 2 ] );

        $this->assertEquals( [
            [
                'id'    => 1,
                'value' => 'Value one!',
            ],
            [
                'id'    => 2,
                'value' => 'Value two!',
            ],
        ], $results );
    }

    /**
     * @return void
     */
    public function testLoad(): void
    {
        $value = $this->loader->load( 1 );

        $this->assertEquals(
            'Value one!',
            $value[ 'value' ]
        );
    }

    /**
     * @return void
     */
    public function testPrime()
    {
        $this->loader->prime( 3, $this->data[ 3 ] );

        $data = $this->loader->getData();

        $this->assertEquals( [
            $this->data[ 3 ],
        ], array_values( $data ) );
    }

    /**
     * @return void
     */
    public function testFlush()
    {
        $this->loader->prime( 3, $this->data[ 3 ] );

        $data = $this->loader->getData();
        $this->assertNotEmpty( $data );

        $this->loader->flush();

        $this->assertEmpty( $this->loader->getData() );
    }
}
