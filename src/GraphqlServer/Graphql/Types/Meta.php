<?php

namespace UnderScorer\GraphqlServer\Graphql\Types;

use Illuminate\Support\Collection;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use UnderScorer\ORM\Contracts\MetaInterface;

/**
 * @Type()
 *
 * Class Meta
 * @package UnderScorer\GraphqlServer\Graphql\Types
 */
class Meta
{

    /**
     * @var MetaInterface
     */
    protected $meta;

    /**
     * Meta constructor.
     *
     * @param MetaInterface $meta
     */
    public function __construct( MetaInterface $meta )
    {
        $this->meta = $meta;
    }

    /**
     * @param Collection $metas
     *
     * @return static[]
     */
    public static function fromCollection( Collection $metas ): array
    {
        return $metas->map( function ( MetaInterface $meta ) {
            return new static( $meta );
        } )->all();
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getValue(): string
    {
        return (string) $this->meta->getMetaValue();
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getKey(): string
    {
        return (string) $this->meta->getMetaKey();
    }

}
