<?php

namespace Test\TodoAppTest\Integration;

use Test\TodoAppTest\TodoAppTestCase;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;

class EditViewActionTest extends TodoAppTestCase
{

    /**
     * @var
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
    public function callsEditPage_Returns200()
    {
        $this->dao->saveTodo(new Todo('Test Name', 'Test message', 'incomplete', new \DateTime()));
        $response = $this->runApp('GET', '/todo/edit/1');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function callsEditPage_WithNotExistsTodo_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->runApp('GET', '/todo/edit/not-exists');
    }
}