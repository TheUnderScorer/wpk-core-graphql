<?php

namespace UnderScorer\GraphqlServer\Tests\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use UnderScorer\Core\Tests\TestCase;
use UnderScorer\GraphqlServer\Data\DataLoader;
use UnderScorer\ORM\Models\User;

/**
 * Class DataLoaderProviderTest
 * @package UnderScorer\GraphqlServer\Tests\Providers
 */
final class DataLoaderProviderTest extends TestCase
{

    /**
     * @throws BindingResolutionException
     */
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
