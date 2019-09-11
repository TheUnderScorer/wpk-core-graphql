<?php

namespace UnderScorer\GraphqlServer\Graphql\Types;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;
use UnderScorer\ORM\Models\Comment as CommentModel;

/**
 * @Type()
 *
 * Class Comment
 * @package UnderScorer\GraphqlServer\Graphql\Types
 */
class Comment extends BaseType
{

    /**
     * @var CommentModel
     */
    protected $comment;

    /**
     * Comment constructor.
     *
     * @param CommentModel $comment
     */
    public function __construct( CommentModel $comment )
    {
        $this->comment = $comment;
    }

    /**
     * @Field()
     *
     * @return User
     */
    public function getAuthor(): User
    {
        return new User( $this->comment->user );
    }

    /**
     * @Field()
     *
     * @return Post
     */
    public function getPost(): Post
    {
        return new Post( $this->comment->post );
    }

    /**
     * @Field()
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->comment->comment_content;
    }

    /**
     * @Field()
     *
     * @return ID
     */
    public function getId(): ID
    {
        return new ID( $this->comment->comment_ID );
    }

    /**
     * @Field()
     *
     * @return string | null
     */
    public function getDate(): ?string
    {
        return $this->comment->comment_date->toDateTimeString();
    }

}
