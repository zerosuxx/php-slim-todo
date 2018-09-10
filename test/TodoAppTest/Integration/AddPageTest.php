<?php

namespace Test\TodoAppTest\Integration;

use Test\TodoAppTest\TodoAppTestCase;
use TodoApp\Dao\TodosDao;
use Zero\Form\Validator\CSRFTokenValidator;

/**
 * Class AddPageTest
 */
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
    public function callsAddPage_Returns301()
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
}