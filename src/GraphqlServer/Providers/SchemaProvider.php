<?php

namespace UnderScorer\GraphqlServer\Providers;

use TheCodingMachine\GraphQLite\SchemaFactory;
use UnderScorer\Core\Providers\ServiceProvider;
use UnderScorer\GraphqlServer\Cache\InMemoryCache;
use UnderScorer\GraphqlServer\Graphql\Auth\AuthenticationService;
use UnderScorer\GraphqlServer\Graphql\Auth\AuthorizationService;

class SchemaProvider extends ServiceProvider
{

    /**
     * Registers service
     */
    public function register(): void
    {
        add_action( 'plugins_loaded', function () {
            $controllerNamespaces = apply_filters( 'wpk.graphql.controllerNamespaces', [] );
            $typeNamespaces       = apply_filters( 'wpk.graphql.typeNamespaces', [] );

            $factory = new SchemaFactory( new InMemoryCache, $this->app );
            $factory
                ->setAuthenticationService( new AuthenticationService )
                ->setAuthorizationService( new AuthorizationService );

            foreach ( $controllerNamespaces as $controllerNamespace ) {
                $factory->addControllerNamespace( $controllerNamespace );
            }

            foreach ( $typeNamespaces as $typeNamespace ) {
                $factory->addTypeNamespace( $typeNamespace );
            }

            $this->app->singleton( SchemaFactory::class, function () use ( $factory ) {
                return $factory;
            } );
        } );
    }

}
