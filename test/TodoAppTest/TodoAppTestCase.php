<?php

namespace Test\TodoAppTest;

use Slim\App;
use SlimSkeleton\AppBuilder;
use Test\AbstractSlimTestCase;
use TodoApp\ConfigProvider;

/**
 * Class TodoAppTestCase
 */
class TodoAppTestCase extends AbstractSlimTestCase
{
    /**
     * @return App
     */
    protected function buildApp(): App
    {
        $appBuilder = new AppBuilder();
        $appBuilder->addProvider(new ConfigProvider());
        return $appBuilder->buildApp();
    }
}