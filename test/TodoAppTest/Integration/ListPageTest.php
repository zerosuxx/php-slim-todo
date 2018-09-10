<?php

namespace Test\TodoAppTest\Integration;

use Test\TodoAppTest\TodoAppTestCase;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;

/**
 * Class ListPageTest
 */
class ListPageTest extends TodoAppTestCase
{
    /**
     * @var TodosDao
     */
    private $dao;

    protected function setUp()
    {
        $this->truncateTable('todos');
        $this->dao = new TodosDao($this->getPDO());
    }

    /**
     * @test
     */
    public function callsListPage_Returns200WithTodos()
    {
        $savedTodo = $this->dao->saveTodo(new Todo('Test Name', 'test message', 'incomplete', new \DateTime()));
        $response = $this->runApp('GET', '/todo/list');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains($savedTodo->getName(), (string)$response->getBody());
        $this->assertContains($savedTodo->getDescription(), (string)$response->getBody());
        $this->assertContains($savedTodo->getStatus(), (string)$response->getBody());
        $this->assertContains($savedTodo->getDueAt()->format('Y-m-d H:i:s'), (string)$response->getBody());
    }
}