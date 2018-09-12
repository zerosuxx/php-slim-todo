<?php

namespace Test\TodoApp;

use SlimSkeleton\AppBuilder;
use Test\AbstractSlimTestCase;
use TodoApp\ConfigProvider;
use TodoApp\Dao\TodosDao;

/**
 * Class TodoAppTestCase
 */
class TodoAppTestCase extends AbstractSlimTestCase
{
    /**
     * @var TodosDao
     */
    protected $todosDao;

    protected function addProvider(AppBuilder $appBuilder)
    {
        $appBuilder->addProvider(new ConfigProvider());
    }

    protected function setUp() {
        parent::setUp();
        $this->truncateTable('todos');
        $this->todosDao = new TodosDao($this->getPDO());
        $_SESSION['errors'] = [];
        $_SESSION['data'] = [];
    }
}