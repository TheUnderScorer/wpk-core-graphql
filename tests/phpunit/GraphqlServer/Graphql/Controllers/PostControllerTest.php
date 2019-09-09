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

        $this->post->meta()->createMany( [
            [
                'meta_key'   => 'test_meta',
                'meta_value' => 'test_value',
            ],
            [
                'meta_key'   => 'test2',
                'meta_value' => 'Test2',
            ],
        ] );

        $this->post->addTerms( 'category', [
            [
                'name' => 'Test cat 1',
                'slug' => 'test_cat_1',
            ],
            [
                'name' => 'Test cat 2',
                'slug' => 'test_cat_2',
            ],
        ] );
    }

    /**
     * @throws BindingResolutionException
     */
    public function testGetPost(): void
    {
        $query = <<<EOL
            query Post(\$ID: ID!, \$taxonomies: [String!]!) {
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
                    },
                    taxonomies(taxonomies: \$taxonomies) {
                        taxonomy,
                        term {
                            name,
                            slug
                        }
                    }
                }
            }
        EOL;

        $result = $this->handleQuery( $query, [
            'ID'         => $this->post->ID,
            'taxonomies' => [ 'category' ],
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

        $this->assertContains( [
            'taxonomy' => 'category',
            'term'     => [
                'name' => 'Test cat 1',
                'slug' => 'test_cat_1',
            ],
        ], $data[ 'taxonomies' ] );
    }

    /**
     * @param string $key
     * @param array  $data
     */
    protected function checkField( string $key, array $data ): void
    {
        $this->assertEquals( $this->post->$key, $data[ $key ] );
    }

    /**
     * @throws BindingResolutionException
     */
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
