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

    /**
     * @test
     */
    public function callsEditPage_GivenErrorsAndData_Returns200WithErrorsAndData()
    {
        $this->dao->saveTodo(new Todo('Test Name', 'Test message', 'incomplete', new \DateTime()));
        $_SESSION['errors'] = ['name' => 'Invalid data'];
        $_SESSION['data'] = ['description' => 'Test desc'];
        $response = $this->runApp('GET', '/todo/edit/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Invalid data', (string)$response->getBody());
        $this->assertContains('Test desc', (string)$response->getBody());
        $this->assertArrayNotHasKey('errors', $_SESSION);
        $this->assertArrayNotHasKey('data', $_SESSION);
    }
}