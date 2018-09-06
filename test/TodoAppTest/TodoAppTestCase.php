<?php

namespace Test\TodoAppTest;

use SlimSkeleton\AppBuilder;
use Test\AbstractSlimTestCase;
use TodoApp\ConfigProvider;

/**
 * Class TodoAppTestCase
 */
class TodoAppTestCase extends AbstractSlimTestCase
{
    protected function addProvider(AppBuilder $appBuilder)
    {
        $appBuilder->addProvider(new ConfigProvider());
    }
}