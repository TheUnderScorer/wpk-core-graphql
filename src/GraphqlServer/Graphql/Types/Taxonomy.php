<?php

namespace UnderScorer\GraphqlServer\Graphql\Types;

use Illuminate\Support\Collection;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;
use UnderScorer\ORM\Models\TermTaxonomy;

/**
 * @Type()
 *
 * Class Taxonomy
 * @package UnderScorer\GraphqlServer\Graphql\Types
 */
class Taxonomy extends BaseType
{

    /**
     * @var TermTaxonomy
     */
    protected $taxonomy;

    /**
     * Taxonomy constructor.
     *
     * @param TermTaxonomy $taxonomy
     */
    public function __construct( TermTaxonomy $taxonomy )
    {
        $this->taxonomy = $taxonomy;
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getTaxonomy(): string
    {
        return $this->taxonomy->taxonomy;
    }

    /**
     * @Field()
     *
     * @return Term
     */
    public function getTerm(): Term
    {
        return new Term( $this->taxonomy->term );
    }

    /**
     * @Field()
     *
     * @return ID
     */
    public function getTermId(): ID
    {
        return new ID( $this->taxonomy->term_id );
    }

    /**
     * @Field()
     *
     * @return ID
     */
    public function getParentId(): ID
    {
        return new ID( $this->taxonomy->parent );
    }

    /**
     * @Field()
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->taxonomy->count;
    }

    /**
     * @Field()
     *
     * @return Post[]
     */
    public function getPosts(): array
    {
        /** @var Collection $posts */
        $posts = $this->taxonomy->posts;

        return Post::fromCollection( $posts );
    }

}
