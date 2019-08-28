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

        $this->user = new User( [
            'user_login' => 'Test_User',
            'user_email' => 'test@gmail.com',
            'user_pass'  => wp_generate_password(),
        ] );

        $this->user->save();

        wp_set_current_user( $this->user->ID );
    }

    /**
     * @throws BindingResolutionException
     */
    public function testCurrentUser(): void
    {
        $query = <<<EOL
            {
                currentUser{
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
            $this->user->user_login,
            $data[ 'login' ]
        );

        $this->assertEquals(
            $this->user->user_email,
            $data[ 'email' ]
        );

        $this->assertEquals(
            $this->user->ID,
            $data[ 'id' ]
        );

    }

}
