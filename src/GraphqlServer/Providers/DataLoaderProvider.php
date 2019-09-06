<?php

namespace UnderScorer\GraphqlServer\Providers;

use UnderScorer\Core\Providers\ServiceProvider;
use UnderScorer\GraphqlServer\Data\DataLoader;
use UnderScorer\ORM\Models\Post;
use UnderScorer\ORM\Models\User;

/**
 * Class DataLoaderProvider
 * @package UnderScorer\GraphqlServer\Providers
 */
class DataLoaderProvider extends ServiceProvider
{

    /**
     * Registers service
     */
    public function register(): void
    {
        $this->userLoader()->postLoader();
    }

    /**
     * @return DataLoaderProvider
     */
    protected function postLoader(): self
    {
        $postBatchFunction = function ( $keys ) {
            return Post::query()->findMany( $keys )->all();
        };

        $postLoader = new DataLoader( $postBatchFunction );

        $this->app->singleton( 'PostDataLoader', function () use ( $postLoader ) {
            return $postLoader;
        } );

        return $this;
    }

    /**
     * @return DataLoaderProvider
     */
    protected function userLoader(): self
    {
        $usersBatchFunction = function ( $keys ) {
            return User::query()->findMany( $keys )->all();
        };

        $userLoader = new DataLoader( $usersBatchFunction );

        $this->app->singleton( 'UserDataLoader', function () use ( $userLoader ) {
            return $userLoader;
        } );

        return $this;
    }
}
