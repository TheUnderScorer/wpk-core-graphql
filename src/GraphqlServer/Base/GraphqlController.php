<?php

namespace UnderScorer\GraphqlServer\Base;

use UnderScorer\Core\Contracts\AppInterface;

/**
 * Class GraphqlController
 * @package UnderScorer\Graphql\Controllers
 */
abstract class GraphqlController
{

    /**
     * @var AppInterface
     */
    protected $app;

    /**
     * GraphqlController constructor.
     *
     * @param AppInterface $app
     */
    public function __construct( ?AppInterface $app )
    {
        $this->app = $app;
    }

}
