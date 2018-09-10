<?php

namespace Test\TodoAppTest\Integration;

use Test\TodoAppTest\TodoAppTestCase;
use TodoApp\Dao\TodosDao;
use Zero\Form\Validator\CSRFTokenValidator;

class AddPageTest extends TodoAppTestCase
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
    public function callsAddPage_GivenValidData_Returns301()
    {
        $_SESSION[CSRFTokenValidator::TOKEN_KEY] = 'token';
        $response = $this->runApp('POST', '/todo/add', [
            'name' => 'Test Name',
            'description' => 'Test message',
            'due_at' => '2018-09-10 10:00:00',
            '_token' => 'token'
        ]);

        $this->assertEquals(301, $response->getStatusCode());

        $todo = $this->dao->getTodo(1);
        $this->assertEquals('Test Name', $todo->getName());
        $this->assertEquals('Test message', $todo->getDescription());
        $this->assertEquals('incomplete', $todo->getStatus());
        $this->assertEquals('2018-09-10 10:00:00', $todo->getDueAt()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function callsAddPage_GivenEmptyData_Returns301()
    {
        $response = $this->runApp('POST', '/todo/add', []);

        $this->assertEquals(301, $response->getStatusCode());

        $todos = $this->dao->getTodos();
        $this->assertCount(0, $todos);
        $this->assertEquals([
            'name' => 'Name can not be empty',
            'description' => 'Description can not be empty',
            'due_at' => 'Due At can not be empty' . "\n" . 'Wrong datetime format',
            '_token' => 'Token mismatch',
        ], $_SESSION['errors']);
    }
}