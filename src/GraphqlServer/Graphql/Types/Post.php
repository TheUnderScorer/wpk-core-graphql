<?php

namespace UnderScorer\GraphqlServer\Graphql\Types;

use Illuminate\Support\Collection;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;
use UnderScorer\ORM\Models\Post as PostModel;

/**
 * @Type()
 *
 * Class Post
 * @package UnderScorer\GraphqlServer\Graphql\Types
 */
class Post
{

    /**
     * @var PostModel
     */
    protected $post;

    /**
     * Post constructor.
     *
     * @param PostModel $post
     */
    public function __construct( PostModel $post )
    {
        $this->post = $post;
    }

    /**
     * @Field()
     *
     * @return ID
     */
    public function getId(): ID
    {
        return new ID( $this->post->ID );
    }

    /**
     * @Field()
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->post->title;
    }

    /**
     * @Field()
     *
     * @return User
     */
    public function getAuthor(): User
    {
        return new User( $this->post->author );
    }

    /**
     * @Field()
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->post->content;
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getContentFiltered(): string
    {
        return apply_filters( 'the_content', $this->post->content );
    }

    /**
     * @Field()
     *
     * @return string | null
     */
    public function getDate(): ?string
    {
        return $this->post->createdAt->toDateTimeString();
    }

    /**
     * @Field()
     *
     * @return string | null
     */
    public function getModifiedDate(): ?string
    {
        return $this->post->updatedAt->toDateTimeString();
    }

    /**
     * @Field()
     *
     * @return Meta[]
     */
    public function getMeta(): array
    {
        /** @var Collection $meta */
        $meta = $this->post->meta;

        return Meta::fromCollection( $meta );
    }

}
