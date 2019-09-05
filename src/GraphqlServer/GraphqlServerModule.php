<?php

namespace UnderScorer\GraphqlServer;

use UnderScorer\GraphqlServer\Base\GraphqlModule;
use UnderScorer\GraphqlServer\Graphql\Controllers\PostController;
use UnderScorer\GraphqlServer\Graphql\Controllers\UserController;
use UnderScorer\GraphqlServer\Hooks\Controllers\Server\ServerHandler;

/**
 *
 *
 * Class GraphqlModule
 * @package UnderScorer\Graphql
 */
class GraphqlServerModule extends GraphqlModule
{

    /**
     * @var array
     */
    protected $graphqlControllers = [
        UserController::class,
        PostController::class,
    ];

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return __NAMESPACE__;
    }

    /**
     * Performs module bootstrap
     *
     * @return void
     */
    protected function bootstrap(): void
    {
        $this->menu->setRegister( false );
    }
}
