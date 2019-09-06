<?php

namespace UnderScorer\GraphqlServer\Data;

/**
 * Interface DataLoaderInterface
 * @package UnderScorer\GraphqlServer\Data
 */
interface DataLoaderInterface
{

    /**
     * Loads value by given key
     *
     * @param string|int $key
     *
     * @return mixed
     */
    public function load( $key );

    /**
     * Loads many values by given array of keys
     *
     * @param array $keys
     *
     * @return mixed
     */
    public function loadMany( array $keys );

    /**
     * Saves value into loader
     *
     * @param string|int $key
     * @param mixed      $value
     *
     * @return self
     */
    public function prime( $key, $value );

    /**
     * Flushes all records from loader
     *
     * @return self
     */
    public function flush();

}
