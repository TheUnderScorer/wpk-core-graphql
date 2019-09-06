<?php

namespace UnderScorer\GraphqlServer\Tests\Graphql\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use UnderScorer\GraphqlServer\Tests\GraphqlTestCase;
use UnderScorer\ORM\Models\User;

/**
 * Class UserControllerTest
 * @package UnderScorer\GraphqlServer\Tests\Graphql\Controllers
 */
final class UserControllerTest extends GraphqlTestCase
{

    /**
     * @var User
     */
    protected $user;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $userID     = $this->factory()->user->create();
        $this->user = User::query()->find( $userID );

        wp_set_current_user( $userID );
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCurrentUser(): void
    {
        $query = <<<EOL
            {
                currentUser {
                    id,
                    login,
                    email,
                    firstName,
                    lastName
                }
            }
        EOL;

        $result = $this->handleQuery( $query );

        $data = $result[ 'data' ][ 'currentUser' ];

        $this->assertEquals(
            $this->user->login,
            $data[ 'login' ]
        );

        $this->assertEquals(
            $this->user->email,
            $data[ 'email' ]
        );

        $this->assertEquals(
            $this->user->ID,
            $data[ 'id' ]
        );

    }

}
