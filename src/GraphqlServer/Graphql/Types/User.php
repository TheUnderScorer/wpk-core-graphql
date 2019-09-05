<?php

namespace UnderScorer\GraphqlServer\Graphql\Types;

use Illuminate\Support\Collection;
use TheCodingMachine\GraphQLite\Annotations\FailWith;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Right;
use TheCodingMachine\GraphQLite\Annotations\Type;
use TheCodingMachine\GraphQLite\Types\ID;
use UnderScorer\ORM\Models\User as UserModel;

/**
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
    protected $user;

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
        return $this->user->login;
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->user->email;
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->user->firstName;
    }

    /**
     * @Field()
     *
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->user->lastName;
    }

    /**
     * @Field()
     * @Right("administrator")
     * @FailWith(null)
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->user->password;
    }

    /**
     * @Field()
     *
     * @return Meta[]
     */
    public function getMeta(): array
    {
        /** @var Collection $meta */
        $meta = $this->user->meta;

        return Meta::fromCollection( $meta );
    }

}
