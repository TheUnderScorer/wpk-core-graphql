<?php

namespace UnderScorer\GraphqlServer\Http\Controllers;

use GraphQL\Error\Error;
use GraphQL\GraphQL;
use Illuminate\Contracts\Container\BindingResolutionException;
use TheCodingMachine\GraphQLite\SchemaFactory;
use UnderScorer\Core\Exceptions\RequestException;
use UnderScorer\Core\Http\Controller;

/**
 * Class GraphqlServer
 * @package UnderScorer\GraphqlServer\Http\Controllers
 */
class GraphqlServer extends Controller
{

    /**
     * Handles request
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle()
    {
        if ( $this->request->getMethod() === 'OPTIONS' ) {
            $this->response->send();
        }

        define( 'IS_GRAPHQL', true );

        $data = $this->getVariables();

        $query     = $data[ 'query' ] ?? '';
        $variables = $data[ 'variables' ] ?? '';

        if ( ! $query ) {
            $this
                ->response
                ->setContent( [
                    'errors' => [
                        [
                            'message' => 'No query provided.',
                        ],
                    ],
                ] )
                ->json();

            return;
        }

        $schemaFactory = $this->app->make( SchemaFactory::class );

        $result = GraphQL::executeQuery( $schemaFactory->createSchema(), $query, null, null, $variables );
        $result->setErrorsHandler( [ $this, 'errorHandler' ] );

        $output = $result->toArray();

        $this->response->setContent( $output )->json();
    }

    /**
     * @return array
     */
    protected function getVariables(): array
    {
        if ( $this->request->query->has( 'query' ) ) {
            return [
                'query'     => $this->request->query->get( 'query' ),
                'variables' => $this->request->query->get( 'variables' ),
            ];
        }

        $input = json_decode( file_get_contents( 'php://input' ), true );

        return empty( $input ) ? [] : $input;
    }

    /**
     * @param Error[] $errors
     *
     * @return array
     */
    public function errorHandler( array $errors ): array
    {
        return array_map( function ( Error $error ) {
            $exception = $error->getPrevious();

            $result = [
                'message' => $error->getMessage(),
                'code'    => 'GRAPHQL_ERROR',
            ];

            if ( $exception instanceof RequestException ) {
                $result[ 'code' ] = $exception->getErrorCode();
            }

            return $result;
        }, $errors );
    }
}
