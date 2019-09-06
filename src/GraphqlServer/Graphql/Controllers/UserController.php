<?php

namespace UnderScorer\GraphqlServer\Graphql\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Types\ID;
use Throwable;
use UnderScorer\GraphqlServer\Base\GraphqlController;
use UnderScorer\GraphqlServer\Data\DataLoader;
use UnderScorer\GraphqlServer\Graphql\Types\Meta;
use UnderScorer\GraphqlServer\Graphql\Types\User;
use UnderScorer\ORM\Models\User as UserModel;
use UnderScorer\ORM\Models\UserMeta;

/**
 * Class UserController
 * @package UnderScorer\Graphql\Graphql\Controller
 */
class UserController extends GraphqlController
{
    /**
     * Retrieves currently logged in user
     *
     * @Query()
     *
     * @return User|null
     * @throws BindingResolutionException
     */
    public function currentUser(): ?User
    {
        if ( ! is_user_logged_in() ) {
            return null;
        }

        /**
         * @var DataLoader $loader
         */
        $loader = $this->app->make( 'UsersDataLoader' );

        /** @var UserModel $user */
        $user = $loader->load( get_current_user_id() );

        return new User( $user );
    }

    /**
     * Creates new user
     *
     * @Mutation()
     *
     * @param string      $login
     * @param string      $email
     * @param string|null $password
     *
     * @return User
     * @throws Throwable
     */
    public function createUser( string $login, string $email, ?string $password ): User
    {
        /**
         * @var DataLoader $loader
         */
        $loader = $this->app->make( 'UsersDataLoader' );

        $user = new UserModel( [
            'user_login' => $login,
            'user_email' => $email,
            'user_pass'  => $password ? wp_hash_password( $password ) : wp_generate_password(),
        ] );

        $user->saveOrFail();

        $loader->prime( $user->ID, $user );

        return new User( $user );
    }

    /**
     * Fetches user by his ID
     *
     * TODO Test
     *
     * @Query()
     *
     * @param ID $ID
     *
     * @return User|null
     * @throws BindingResolutionException
     */
    public function user( ID $ID ): ?User
    {
        /**
         * @var DataLoader $loader
         */
        $loader = $this->app->make( 'UsersDataLoader' );

        /**
         * @var UserModel $user
         */
        $user = $loader->load( $ID->val() );

        if ( ! $user ) {
            return null;
        }

        return new User( $user );
    }

    /**
     * TODO Test
     *
     * @Query()
     *
     * @param string $key
     * @param ID     $userID
     *
     * @return Meta[]
     */
    public function userMeta( string $key, ID $userID ): array
    {
        $meta = UserMeta::query()
                        ->where( 'user_id', '=', (int) $userID->val() )
                        ->where( 'meta_key', '=', $key )
                        ->get();

        $meta = apply_filters( 'wpk.graphql.userMeta', $meta );

        return Meta::fromCollection( $meta );
    }
}
