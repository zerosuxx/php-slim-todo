<?php

namespace SlimSkeleton;

use Slim\App;

/**
 * Class AppBuilder
 */
class AppBuilder
{

    /**
     * @var array
     */
    private $providers = [];

    public function addProvider(callable $provider)
    {
        $this->providers[] = $provider;
    }

    public function buildApp($container = []): App
    {
        $app = new App($container);
        foreach ($this->providers as $provider) {
            $provider($app);
        }
        return $app;
    }

}