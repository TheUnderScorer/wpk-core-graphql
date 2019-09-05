<?php

namespace UnderScorer\GraphqlServer\Graphql\Controllers;

use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Types\ID;
use Throwable;
use UnderScorer\GraphqlServer\Base\GraphqlController;
use UnderScorer\GraphqlServer\Graphql\Types\User;
use UnderScorer\ORM\Models\User as UserModel;

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
     */
    public function currentUser(): ?User
    {
        if ( ! is_user_logged_in() ) {
            return null;
        }

        /** @var UserModel $user */
        $user = UserModel::query()->findOrFail( get_current_user_id() );

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
        $user = new UserModel( [
            'user_login' => $login,
            'user_email' => $email,
            'user_pass'  => $password ? wp_hash_password( $password ) : wp_generate_password(),
        ] );

        $user->saveOrFail();

        return new User( $user );
    }

    /**
     * Fetches user by his ID
     *
     * @Query()
     *
     * @param ID $ID
     *
     * @return User|null
     */
    public function getUser( ID $ID ): ?User
    {
        /**
         * @var UserModel $user
         */
        $user = UserModel::query()->findOrFail( $ID->val() );

        return new User( $user );
    }
}
