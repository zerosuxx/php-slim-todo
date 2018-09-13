<?php

namespace Test\TodoApp;

use DateTime;
use SlimSkeleton\AppBuilder;
use Test\AbstractSlimTestCase;
use TodoApp\ConfigProvider;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;
use Zero\Storage\ArrayStorage;

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
    }


    protected function loadArrayStorageToSession(): ArrayStorage {
        /* @var $container \Slim\Container */
        $container = $this->getApp()->getContainer();
        $container['session'] = new ArrayStorage();
        return $container['session'];
    }

    protected function buildTodo(
        $name,
        $description,
        DateTime $dueAt = null,
        $status = Todo::STATUS_INCOMPLETE,
        $id = null
    ): Todo  {
        return new Todo($name, $description, $dueAt ?: new \DateTime(), $status, $id);
    }
}