<?php

namespace UnderScorer\GraphqlServer\Graphql\Auth;

use TheCodingMachine\GraphQLite\Security\AuthenticationServiceInterface;
use UnderScorer\ORM\Models\User;

/**
 * Class AuthenticationService
 * @package UnderScorer\GraphqlServer\Graphql\Auth
 */
class AuthenticationService implements AuthenticationServiceInterface
{

    /**
     * Returns true if the "current" user is logged
     *
     * @return bool
     */
    public function isLogged(): bool
    {
        return is_user_logged_in();
    }

    /**
     * Returns an object representing the current logged user.
     * Can return null if the user is not logged.
     */
    public function getUser(): ?object
    {
        return User::current();
    }
}
