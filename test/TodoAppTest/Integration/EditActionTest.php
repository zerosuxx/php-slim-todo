<?php

namespace Test\TodoAppTest\Integration;

use Test\TodoAppTest\TodoAppTestCase;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;
use Zero\Form\Validator\CSRFTokenValidator;

class EditActionTest extends TodoAppTestCase
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
    public function callsEditPage_GivenValidData_Returns301()
    {
        $this->dao->saveTodo(new Todo('Test Name', 'Test message', 'incomplete', new \DateTime()));
        $_SESSION[CSRFTokenValidator::TOKEN_KEY] = 'token';
        $response = $this->runApp('POST', '/todo/edit/1', [
            'name' => 'Test Name 1',
            'description' => 'Test message 1',
            'due_at' => '2019-09-10 10:00:00',
            '_token' => 'token'
        ]);

        $this->assertEquals(301, $response->getStatusCode());

        $todo = $this->dao->getTodo(1);
        $this->assertEquals('Test Name 1', $todo->getName());
        $this->assertEquals('Test message 1', $todo->getDescription());
        $this->assertEquals('incomplete', $todo->getStatus());
        $this->assertEquals('2019-09-10 10:00:00', $todo->getDueAt()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function callsEditPage_GivenInValidData_Returns301()
    {
        $response = $this->runApp('POST', '/todo/edit/1', [
        ]);

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

    /**
     * @test
     */
    public function callsEditPage_WithNotExistsTodo_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $_SESSION[CSRFTokenValidator::TOKEN_KEY] = 'token';
        $this->runApp('POST', '/todo/edit/not-exists', [
            'name' => 'Test Name 1',
            'description' => 'Test message 1',
            'due_at' => '2019-09-10 10:00:00',
            '_token' => 'token'
        ]);
    }
}