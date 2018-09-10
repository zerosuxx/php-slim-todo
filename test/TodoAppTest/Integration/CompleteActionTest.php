<?php

namespace Test\TodoAppTest\Integration;

use Test\TodoAppTest\TodoAppTestCase;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;

class CompleteActionTest extends TodoAppTestCase
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
    public function callsCompletePage_GivenValidData_Returns301()
    {
        $this->dao->saveTodo(new Todo('Test Name', 'Test message', 'incomplete', new \DateTime()));
        $response = $this->runApp('POST', '/todo/complete/1');

        $this->assertEquals(301, $response->getStatusCode());

        $todo = $this->dao->getTodo(1);
        $this->assertEquals('complete', $todo->getStatus());
    }

    /**
     * @test
     */
    public function callsCompletePage_WithNotExistsTodo_Returns301()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->runApp('POST', '/todo/complete/not-exists');
    }
}