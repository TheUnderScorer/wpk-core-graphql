<?php

namespace UnderScorer\GraphqlServer\Graphql\Types;

use Illuminate\Support\Collection;

/**
 * Class BaseType
 * @package UnderScorer\GraphqlServer\Graphql\Types
 */
abstract class BaseType
{

    /**
     * @param Collection $collection
     *
     * @return static[]
     */
    public static function fromCollection( Collection $collection ): array
    {
        return $collection
            ->map( function ( $item ) {
                return new static( $item );
            } )
            ->all();
    }

}
