<?php

namespace UnderScorer\GraphqlServer\Tests\Http\Controllers;

use GraphQL\Error\Error;
use Illuminate\Contracts\Container\BindingResolutionException;
use UnderScorer\Core\Exceptions\RequestException;
use UnderScorer\Core\Http\Request;
use UnderScorer\Core\Tests\Mock\Http\MockResponse;
use UnderScorer\GraphqlServer\Http\Controllers\GraphqlServer;
use UnderScorer\GraphqlServer\Tests\GraphqlTestCase;

/**
 * Class GraphqlServerTest
 * @package UnderScorer\GraphqlServer\Tests\Http\Controllers
 */
final class GraphqlServerTest extends GraphqlTestCase
{
    /**
     * @throws BindingResolutionException
     *
     */
    public function testHandle(): void
    {
        $request  = new Request();
        $response = new MockResponse();

        $controller = self::$app->make( GraphqlServer::class );
        $controller->setRequest( $request )->setResponse( $response );

        $controller->handle();

        $result = json_decode( $response->getContent(), true );

        $this->assertTrue( IS_GRAPHQL );

        $this->assertEquals(
            'No query provided.',
            $result[ 'errors' ][ 0 ][ 'message' ]
        );
    }

    /**
     * @throws BindingResolutionException
     */
    public function testBatchQuery(): void
    {
        $userID = $this->login( 'administrator' );

        $query = <<<EOL
            {
                currentUser {
                    id,
                    login,
                    email,
                    firstName,
                    lastName
                }
                user(ID: $userID) {
                    id,
                }
            }
        EOL;

        $result = $this->handleQuery( $query );

        $this->assertNotEmpty( $result[ 'data' ][ 'currentUser' ] );
        $this->assertNotEmpty( $result[ 'data' ][ 'user' ] );
    }

    /**
     * @throws BindingResolutionException
     */
    public function testErrorHandler(): void
    {
        $controller   = self::$app->make( GraphqlServer::class );
        $requestError = new RequestException( 'Server error!', 'INTERNAL_ERROR' );
        $graphqlError = new Error( $requestError->getMessage(), [], null, null, '', $requestError );

        $formattedErrors = $controller->errorHandler( [ $graphqlError ] );

        $this->assertContains( [
            'message' => 'Server error!',
            'code'    => 'INTERNAL_ERROR',
        ], $formattedErrors );
    }
}
