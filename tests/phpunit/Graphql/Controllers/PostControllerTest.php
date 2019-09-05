<?php

namespace UnderScorer\GraphqlServer\Tests\Graphql\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use UnderScorer\GraphqlServer\Tests\GraphqlTestCase;
use UnderScorer\ORM\Models\Post;

/**
 * Class PostControllerTest
 * @package UnderScorer\GraphqlServer\Tests\Graphql\Controllers
 */
final class PostControllerTest extends GraphqlTestCase
{

    /**
     * @var Post
     */
    protected $post;

    /**
     * @throws BindingResolutionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $postID = $this->factory()->post->create();
        $userID = $this->factory()->user->create();
        wp_set_current_user( $userID );

        $this->post           = Post::query()->find( $postID );
        $this->post->authorID = $userID;

        $this->post->save();

        $this->post->addMeta( 'test_meta', 'test_value' );
    }

    /**
     * @throws BindingResolutionException
     */
    public function testGetPost(): void
    {
        $query = <<<EOL
            query Post(\$ID: ID!) {
                post(ID: \$ID) {
                    id,
                    title,
                    content,
                    date,
                    author {
                        id,
                    },
                    meta {
                        key,
                        value
                    }
                }
            }
        EOL;

        $result = $this->handleQuery( $query, [
            'ID' => $this->post->ID,
        ] );

        $this->assertArrayNotHasKey( 'errors', $result );

        $data = $result[ 'data' ][ 'post' ];

        $this->assertEquals( $this->post->ID, $data[ 'id' ] );

        $this->checkField( 'title', $data );
        $this->checkField( 'content', $data );

        $this->assertEquals(
            $this->post->createdAt->toDateTimeString(),
            $data[ 'date' ]
        );

        $this->assertEquals(
            $this->post->authorID,
            $data[ 'author' ][ 'id' ]
        );

        $this->assertContains( [
            'key'   => 'test_meta',
            'value' => 'test_value',
        ], $data[ 'meta' ] );
    }

    /**
     * @param string $key
     * @param array  $data
     */
    protected function checkField( string $key, array $data ): void
    {
        $this->assertEquals( $this->post->$key, $data[ $key ] );
    }

    public function testDeletePost(): void
    {
        $query = <<<EOL
            mutation DeletePost(\$ID: ID!) {
                deletePost(ID: \$ID) {
                    id
                }
            }
        EOL;

        $result = $this->handleQuery( $query, [
            'ID' => $this->post->ID,
        ] );

        $data = $result[ 'data' ][ 'deletePost' ];

        $this->assertEquals( $this->post->ID, $data[ 'id' ] );

        $this->setExpectedException( ModelNotFoundException::class );

        $this->post->refresh();
    }

}
