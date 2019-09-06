<?php

namespace UnderScorer\GraphqlServer\Tests\Providers;

use AndrewDalpino\DataLoader\BatchingDataLoader;
use UnderScorer\Core\Tests\TestCase;

class DataLoaderProviderTest extends TestCase
{

    public function testUserDataLoader(): void
    {
        $userID = $this->factory()->user->create();

        /** @var BatchingDataLoader $loader */
        $loader = self::$app->make( 'UserDataLoader' );

        $loadedUser = $loader->load( $userID );

        return;
    }

}
