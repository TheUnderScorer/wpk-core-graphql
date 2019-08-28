<?php

namespace UnderScorer\GraphqlServer\Tests\Graphql\Auth;

use UnderScorer\Core\Tests\TestCase;
use UnderScorer\GraphqlServer\Graphql\Auth\AuthorizationService;

/**
 * Class AuthorizationServiceTest
 * @package UnderScorer\GraphqlServer\Tests\Graphql\Auth
 */
final class AuthorizationServiceTest extends TestCase
{

    /**
     * @var AuthorizationService
     */
    protected $service;

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->service = new AuthorizationService();
    }

    /**
     * @covers AuthorizationService::isAllowed
     */
    public function testIsAllowedAdministrator(): void
    {
        $this->login( 'administrator' );

        $result = $this->service->isAllowed( 'administrator' );

        $this->assertTrue( $result );
    }

    /**
     * @covers AuthorizationService::isAllowed
     */
    public function testIsAllowedAdministratorForUser(): void
    {
        $this->login( 'user' );

        $result = $this->service->isAllowed( 'administrator' );

        $this->assertFalse( $result );
    }
}
