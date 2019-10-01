<?php

namespace UnderScorer\GraphqlServer\Base;

use UnderScorer\Core\Contracts\AppInterface;
use UnderScorer\Core\Module;

/**
 * Class BaseGraphqlController
 * @package UnderScorer\Graphql\Base
 */
abstract class GraphqlModule extends Module
{

    /**
     * @var array
     */
    protected $graphqlControllers = [];

    /**
     * @var array
     */
    private $graphqlNamespaces = [];

    /**
     * BaseGraphqlModule constructor.
     *
     * @param string       $ID
     * @param AppInterface $app
     */
    public function __construct( string $ID, AppInterface $app )
    {
        parent::__construct( $ID, $app );

        $this->setupNamespaces()->loadGraphqlControllers();
    }

    /**
     * @return $this
     */
    private function loadGraphqlControllers(): self
    {
        foreach ( $this->graphqlControllers as $graphqlController ) {
            $this->app->singleton( $graphqlController );
        }

        return $this;
    }

    /**
     * Setups module graphql controllers and types namespaces
     *
     * @return self
     */
    private function setupNamespaces(): self
    {
        $namespace = $this->getNamespace();

        $this->graphqlNamespaces = [
            'controllers' => [
                $namespace . '\\Graphql\\Controllers\\',
            ],
            'types'       => [
                $namespace . '\\Graphql\\Types\\',
            ],
        ];

        $this->loadNamespaces();

        return $this;
    }

    /**
     * @return string
     */
    abstract public function getNamespace(): string;

    /**
     * @return void
     */
    private function loadNamespaces(): void
    {
        add_filter( 'wpk.graphql.controllerNamespaces', function ( array $namespaces ) {
            $namespaces = array_merge( $namespaces, $this->graphqlNamespaces[ 'controllers' ] );

            return $namespaces;
        } );

        add_filter( 'wpk.graphql.typeNamespaces', function ( array $namespaces ) {
            $namespaces = array_merge( $namespaces, $this->graphqlNamespaces[ 'types' ] );

            return $namespaces;
        } );
    }

}
