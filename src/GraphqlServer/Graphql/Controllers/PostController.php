<?php

namespace UnderScorer\GraphqlServer\Graphql\Controllers;

use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Types\ID;
use UnderScorer\GraphqlServer\Base\GraphqlController;
use UnderScorer\GraphqlServer\Graphql\Types\Post;
use UnderScorer\ORM\Models\Post as PostModel;

/**
 *
 * Class PostController
 * @package UnderScorer\GraphqlServer\Graphql\Controllers
 */
class PostController extends GraphqlController
{

    /**
     * Fetches post by given ID
     *
     * @Query()
     *
     * @param ID $ID
     *
     * @return Post
     */
    public function post( ID $ID ): Post
    {
        /** @var PostModel $foundPost */
        $foundPost = PostModel::query()->findOrFail( $ID->val() );

        return new Post( $foundPost );
    }

}
