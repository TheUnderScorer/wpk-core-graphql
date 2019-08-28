<?php

namespace UnderScorer\GraphqlServer\Graphql\Auth;

use TheCodingMachine\GraphQLite\Security\AuthorizationServiceInterface;

/**
 * Class AuthorizationService
 * @package UnderScorer\GraphqlServer\Graphql\Auth
 */
class AuthorizationService implements AuthorizationServiceInterface
{

    /**
     * Returns true if the "current" user has access to the right "$right"
     *
     * @param string $right
     *
     * @return bool
     */
    public function isAllowed( string $right ): bool
    {
        if ( ! is_user_logged_in() ) {
            return false;
        }

        return apply_filters(
            "wpk.graphql.isAllowed.{$right}",
            current_user_can( $right ),
            $right );
    }
}
