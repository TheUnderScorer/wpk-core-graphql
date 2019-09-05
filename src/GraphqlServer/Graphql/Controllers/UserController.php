<?php

namespace UnderScorer\GraphqlServer\Graphql\Controllers;

use TheCodingMachine\GraphQLite\Annotations\FailWith;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Right;
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
        return is_user_logged_in() ? new User( UserModel::current() ) : null;
    }

    /**
     * Creates new user
     *
     * @Mutation()
     *
     * @param string $login
     * @param string $email
     *
     * @return User
     * @throws Throwable
     */
    public function createUser( string $login, string $email ): User
    {
        $user = new UserModel( [
            'user_login' => $login,
            'user_email' => $email,
            'user_pass'  => wp_generate_password(),
        ] );

        $user->saveOrFail();

        return new User( $user );
    }

    /**
     * Updates given user
     *
     * @Mutation()
     * @Right("administrator")
     * @FailWith(null)
     *
     * @param ID     $ID
     * @param string $login
     *
     * @return User | null
     * @throws Throwable
     */
    public function updateUser( ID $ID, string $login ): ?User
    {
        /**
         * @var UserModel $modifiedUser
         */
        $modifiedUser = UserModel::query()->find( (int) $ID->val() );

        $modifiedUser->user_login = $login;
        $modifiedUser->saveOrFail();

        return new User( $modifiedUser );
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
