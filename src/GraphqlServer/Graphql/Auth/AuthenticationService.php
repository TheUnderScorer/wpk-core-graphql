<?php

namespace UnderScorer\GraphqlServer\Graphql\Auth;

use TheCodingMachine\GraphQLite\Security\AuthenticationServiceInterface;

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
}
