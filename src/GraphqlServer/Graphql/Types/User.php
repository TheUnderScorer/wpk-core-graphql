<?php

namespace UnderScorer\GraphqlServer\Graphql\Types;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;
use UnderScorer\ORM\Models\User as UserModel;

/**
 * Proxy for user model
 *
 * @Type()
 *
 * Class User
 * @package UnderScorer\Graphql\Graphql\Types
 */
class User
{

    /**
     * @var UserModel
     */
    private $user;

    /**
     * User constructor.
     *
     * @param UserModel $user
     */
    public function __construct( UserModel $user )
    {
        $this->user = $user;
    }

    /**
     * @Field()
     *
     * @return ID
     */
    public function getId(): ?ID
    {
        return new ID( $this->user->ID );
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getLogin(): ?string
    {
        return $this->user->user_login;
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->user->user_email;
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->user->first_name;
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->user->last_name;
    }

}
