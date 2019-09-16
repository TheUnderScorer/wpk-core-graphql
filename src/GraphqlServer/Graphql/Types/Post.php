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
class Post extends BaseType
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
    public function getTitle(): string
    {
        return $this->post->title;
    }

    /**
     * @Field()
     *
     * @return User
     */
    public function getAuthor(): ?User
    {
        return $this->post->author ? new User( $this->post->author ) : null;
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
    public function getContentFiltered(): ?string
    {
        return apply_filters( 'the_content', $this->post->content );
    }

    /**
     * @Field()
     *
     * @return string|null
     */
    public function getThumbnail(): ?string
    {
        $thumbnail = $this->post->thumbnail;

        if ( ! $thumbnail ) {
            return null;
        }

        return $thumbnail ?
            $thumbnail->meta_value :
            null;
    }

    /**
     * @Field()
     *
     * @return Post[]
     */
    public function getChildren(): array
    {
        $children = $this->post->children;

        return $children ?
            Post::fromCollection( $children ) :
            [];
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->post->status;
    }

    /**
     * @Field()
     *
     * @return Post|null
     */
    public function getParent(): ?Post
    {
        $parent = $this->post->parent;

        return $parent ?
            new Post( $parent ) :
            null;
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

    /**
     * @Field()
     *
     * @return string
     */
    public function getCommentStatus(): string
    {
        return $this->post->commentStatus;
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getCommentCount(): string
    {
        return $this->post->commentCount;
    }

    /**
     * @Field()
     *
     * @return Comment[]
     */
    public function getComments(): array
    {
        /** @var Collection $comments */
        $comments = $this->post->comments;

        return $comments ?
            Comment::fromCollection( $comments ) :
            [];
    }

    /**
     * @Field()
     *
     * @param string[] $taxonomies
     *
     * @return Taxonomy[]
     */
    public function getTaxonomies( array $taxonomies ): array
    {
        $tax = $this->post->taxonomy( $taxonomies );

        return Taxonomy::fromCollection( $tax );
    }

}
