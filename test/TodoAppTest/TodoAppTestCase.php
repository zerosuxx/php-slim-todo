<?php

namespace Test\TodoAppTest;

use Slim\App;
use TodoApp\ConfigProvider;

/**
 * Class TodoAppTestCase
 */
class TodoAppTestCase extends \Test\AbstractSlimTestCase
{
    /**
     * @return App
     */
    protected function buildApp(): App
    {
        $app = new App();
        new ConfigProvider($app);
        return $app;
    }
}