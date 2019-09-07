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
        $postBatchFunction = function ( $keys, $model = Post::class ) {
            return $model::query()->findMany( $keys )->all();
        };

        $this->app->singleton( 'PostsDataLoader', function () use ( $postBatchFunction ) {
            return new DataLoader( $postBatchFunction );
        } );

        return $this;
    }

    /**
     * @return DataLoaderProvider
     */
    protected function userLoader(): self
    {
        $usersBatchFunction = function ( $keys, $model = User::class ) {
            return $model::query()->findMany( $keys )->all();
        };

        $this->app->singleton( 'UsersDataLoader', function () use ( $usersBatchFunction ) {
            return new DataLoader( $usersBatchFunction );
        } );

        return $this;
    }
}
