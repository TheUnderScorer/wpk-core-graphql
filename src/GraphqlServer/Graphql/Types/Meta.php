<?php

namespace UnderScorer\GraphqlServer\Graphql\Types;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use UnderScorer\ORM\Contracts\MetaInterface;

/**
 * @Type()
 *
 * Class Meta
 * @package UnderScorer\GraphqlServer\Graphql\Types
 */
class Meta extends BaseType
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
     * @Field()
     *
     * @return string
     */
    public function getValue(): string
    {
        $value = $this->meta->getMetaValue();

        return is_array( $value ) || is_object( $value ) ? json_encode( $value ) : (string) $value;
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
