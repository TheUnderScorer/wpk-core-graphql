<?php

namespace UnderScorer\GraphqlServer\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use UnderScorer\Core\Http\Request;
use UnderScorer\Core\Tests\Mock\Http\MockResponse;
use UnderScorer\Core\Tests\TestCase;
use UnderScorer\GraphqlServer\Http\Controllers\GraphqlServer;

/**
 * Class GraphqlTestCase
 * @package UnderScorer\GraphqlServer\Tests
 */
class GraphqlTestCase extends TestCase
{

    /**
     * @var GraphqlServer
     */
    private $server;

    /**
     * @throws BindingResolutionException
     */
    public function setUp()
    {
        parent::setUp();

        $this->server = self::$app->make( GraphqlServer::class );
    }

    /**
     * @param string $query
     * @param array  $variables
     *
     * @return array
     * @throws BindingResolutionException
     */
    public function handleQuery( string $query, array $variables = [] ): array
    {
        $request  = new Request();
        $response = new MockResponse();

        $request->query->add( [
            'query'     => $query,
            'variables' => $variables,
        ] );

        $this->server
            ->setRequest( $request )
            ->setResponse( $response )
            ->handle();

        $response = json_decode( $response->getContent(), true );

        return $response;
    }
}
