<?php

namespace Test\TodoAppTest\Integration;

use Test\TodoAppTest\TodoAppTestCase;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;
use Zero\Form\Validator\CSRFTokenValidator;

/**
 * Class AddPageTest
 */
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
    public function callsAddPage_GivenValidData_Returns301()
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
}