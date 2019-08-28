<?php

use UnderScorer\Core\Http\Router;
use UnderScorer\GraphqlServer\Http\Controllers\GraphqlServer;

/**
 * @var Router $router
 */

$router
    ->route()
    ->any()
    ->match( '/graphql' )
    ->controller( GraphqlServer::class );
