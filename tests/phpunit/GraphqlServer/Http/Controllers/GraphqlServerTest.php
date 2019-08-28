<?php

namespace UnderScorer\GraphqlServer\Tests\Http\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use UnderScorer\Core\Http\Request;
use UnderScorer\Core\Tests\Mock\Http\MockResponse;
use UnderScorer\Core\Tests\TestCase;
use UnderScorer\GraphqlServer\Hooks\Controllers\Server\ServerHandler;
use UnderScorer\GraphqlServer\Http\Controllers\GraphqlServer;

final class GraphqlServerTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     *
     * @covers ServerHandler::handle()
     * @covers ServerHandler::shouldHandle()
     */
    public function testIsListeningForRequests(): void
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
}
