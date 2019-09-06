<?php

namespace UnderScorer\GraphqlServer\Tests\Providers;

use UnderScorer\Core\Tests\TestCase;
use UnderScorer\GraphqlServer\Data\DataLoader;
use UnderScorer\ORM\Models\User;

final class DataLoaderProviderTest extends TestCase
{

    public function testUserDataLoader(): void
    {
        $userID = $this->factory()->user->create();

        /** @var DataLoader $loader */
        $loader = self::$app->make( 'UserDataLoader' );

        $loadedUser = $loader->load( $userID );

        $this->assertInstanceOf( User::class, $loadedUser );
        $this->assertEquals( $userID, $loadedUser->ID );
    }

}
