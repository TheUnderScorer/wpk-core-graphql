<?php

namespace UnderScorer\GraphqlServer\Data;

/**
 * Class DataLoader
 * @package UnderScorer\GraphqlServer\Data
 */
class DataLoader implements DataLoaderInterface
{

    /**
     * Stores current loader values
     *
     * @var array
     */
    protected $data = [];

    /**
     * Function for retrieving values
     *
     * @var callable
     */
    protected $loadFunction;

    /**
     * DataLoader constructor.
     *
     * @param callable $loadFunction
     */
    public function __construct( callable $loadFunction )
    {
        $this->loadFunction = $loadFunction;
    }

    /**
     * Loads value by given key
     *
     * @param string|int $key
     *
     * @return mixed
     */
    public function load( $key )
    {
        if ( isset( $this->data[ $key ] ) ) {
            return $this->data[ $key ];
        }

        $value = call_user_func( $this->loadFunction, [ $key ] );

        if ( is_array( $value ) ) {
            $value = array_shift( $value );
        }

        $this->prime( $key, $value );

        return $value;
    }

    /**
     * Saves value into loader
     *
     * @param string|int $key
     * @param mixed      $value
     *
     * @return self
     */
    public function prime( $key, $value )
    {
        if ( isset( $this->data[ $key ] ) ) {
            return $value;
        }

        $this->data[ $key ] = $value;

        return $this;
    }

    /**
     * Loads many values by given array of keys
     *
     * @param array $keys
     *
     * @return mixed
     */
    public function loadMany( array $keys )
    {
        $values = call_user_func( $this->loadFunction, $keys );

        foreach ( $keys as $index => $key ) {
            $this->prime(
                $key,
                $values[ $index ]
            );
        }

        return $values;
    }

    /**
     * Flushes all records from loader
     *
     * @return self
     */
    public function flush()
    {
        $this->data = [];

        return $this;
    }

    /**
     * Returns currently stored data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
