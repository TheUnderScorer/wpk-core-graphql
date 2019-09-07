<?php

namespace UnderScorer\GraphqlServer;

use UnderScorer\Core\Contracts\AppInterface;
use UnderScorer\GraphqlServer\Base\GraphqlModule;
use UnderScorer\GraphqlServer\Graphql\Controllers\PostController;
use UnderScorer\GraphqlServer\Graphql\Controllers\UserController;

/**
 * Class GraphqlModule
 * @package UnderScorer\Graphql
 */
class GraphqlServerModule extends GraphqlModule
{

    /**
     * GraphqlServerModule constructor.
     *
     * @param string       $ID
     * @param AppInterface $app
     */
    public function __construct( string $ID, AppInterface $app )
    {
        $this->graphqlControllers = apply_filters( 'wpk.graphql.baseControllers', [
            UserController::class,
            PostController::class,
        ] );

        parent::__construct( $ID, $app );
    }

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
