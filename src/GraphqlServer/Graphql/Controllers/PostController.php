<?php

namespace UnderScorer\GraphqlServer\Graphql\Controllers;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Types\ID;
use UnderScorer\Core\Exceptions\RequestException;
use UnderScorer\GraphqlServer\Base\GraphqlController;
use UnderScorer\GraphqlServer\Data\DataLoader;
use UnderScorer\GraphqlServer\Graphql\Types\Post;
use UnderScorer\ORM\Models\Post as PostModel;
use UnderScorer\ORM\Models\User;

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
     * @throws BindingResolutionException
     */
    public function post( ID $ID ): Post
    {
        /** @var DataLoader $postsLoader */
        $postsLoader = $this->app->make( 'PostsDataLoader' );

        /** @var PostModel $foundPost */
        $foundPost = $postsLoader->load( $ID->val() );

        return new Post( $foundPost );
    }

    /**
     * Removes selected post
     *
     * @Mutation()
     *
     * @param ID $ID
     *
     * @return Post | null
     * @throws RequestException
     * @throws Exception
     */
    public function deletePost( ID $ID ): ?Post
    {
        /** @var DataLoader $postsLoader */
        $postsLoader = $this->app->make( 'PostsDataLoader' );

        $currentUser = User::current();

        /** @var PostModel $postToDelete */
        $postToDelete = $postsLoader->load( $ID->val() );

        if ( ! $postToDelete ) {
            return null;
        }

        if ( ! $currentUser ||
             ( $currentUser->ID !== (int) $postToDelete->authorID && ! current_user_can( 'administrator' ) ) ||
             ! apply_filters( 'wpk.graphql.canDeletePost', $postToDelete, $currentUser )
        ) {
            throw new RequestException( 'You cannot delete this post.' );
        }

        $result = new Post( $postToDelete );
        $postToDelete->delete();

        return $result;
    }

}
