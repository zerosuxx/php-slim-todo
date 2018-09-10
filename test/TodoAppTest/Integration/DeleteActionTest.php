<?php

namespace Test\TodoAppTest\Integration;

use DateTime;
use InvalidArgumentException;
use Test\TodoAppTest\TodoAppTestCase;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;

class DeleteActionTest extends TodoAppTestCase
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
    public function callsDeletePage_GivenValidData_Returns301()
    {
        $this->dao->saveTodo(new Todo('Test Name', 'Test message', 'incomplete', new DateTime()));
        $response = $this->runApp('POST', '/todo/delete/1');

        $this->assertEquals(301, $response->getStatusCode());

        $todos = $this->dao->getTodos();
        $this->assertCount(0, $todos);
    }

    /**
     * @test
     */
    public function callsDeletePage_WithNotExistsTodo_ThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->runApp('POST', '/todo/delete/not-exists');
    }
}