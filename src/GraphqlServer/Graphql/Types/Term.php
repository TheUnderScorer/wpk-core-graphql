<?php

namespace UnderScorer\GraphqlServer\Graphql\Types;

use Illuminate\Support\Collection;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;
use UnderScorer\ORM\Models\Term as TermModel;

/**
 * @Type()
 *
 * Class Term
 * @package UnderScorer\GraphqlServer\Graphql\Types
 */
class Term extends BaseType
{

    /**
     * @var TermModel
     */
    protected $term;

    /**
     * Term constructor.
     *
     * @param TermModel $term
     */
    public function __construct( TermModel $term )
    {
        $this->term = $term;
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getSlug(): string
    {
        return $this->term->slug;
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->term->name;
    }

    /**
     * @Field()
     *
     * @return ID
     */
    public function getId(): ID
    {
        return new ID( $this->term->term_id );
    }

    /**
     * @param array $keys
     *
     * @return Meta[]
     */
    public function getMeta( array $keys = [] ): array
    {
        /** @var Collection $meta */
        $meta = $this->term->meta;

        return Meta::fromCollection( $meta );
    }

    /**
     * @Field()
     *
     * @return Taxonomy
     */
    public function getTaxonomy(): Taxonomy
    {
        return new Taxonomy( $this->term->taxonomy );
    }

}
