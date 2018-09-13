<?php

namespace Test\TodoApp\Integration;

use Test\TodoApp\TodoAppTestCase;
use Zero\Form\Validator\CSRFTokenValidator;

class EditActionTest extends TodoAppTestCase
{

    /**
     * @test
     */
    public function callsEditPage_GivenValidData_Returns301()
    {
        $this->todosDao->saveTodo($this->buildTodo('Test Name', 'Test message'));
        $_SESSION[CSRFTokenValidator::TOKEN_KEY] = 'token';
        $response = $this->runApp('PATCH', '/todo/1', [
            'name' => 'Test Name 1',
            'description' => 'Test message 1',
            'due_at' => '2019-09-10 10:00:00',
            '_token' => 'token'
        ]);

        $this->assertEquals(301, $response->getStatusCode());

        $todo = $this->todosDao->getTodo(1);
        $this->assertEquals('Test Name 1', $todo->getName());
        $this->assertEquals('Test message 1', $todo->getDescription());
        $this->assertEquals('incomplete', $todo->getStatus());
        $this->assertEquals('2019-09-10 10:00:00', $todo->getDueAtTimestamp());
    }

    /**
     * @test
     */
    public function callsEditPage_GivenEmptyData_Returns301()
    {
        $storage = $this->loadArrayStorageToSession();
        $response = $this->runApp('PATCH', '/todo/1');

        $this->assertEquals(301, $response->getStatusCode());

        $todos = $this->todosDao->getTodos();
        $this->assertCount(0, $todos);
        $this->assertEquals([
            'name' => 'Name can not be empty',
            'description' => 'Description can not be empty',
            'due_at' => 'Due At can not be empty' . "\n" . 'Wrong datetime format',
            '_token' => 'Token mismatch',
        ], $storage->get('errors'));
    }

    /**
     * @test
     */
    public function callsEditPage_WithNotExistsTodo_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $_SESSION[CSRFTokenValidator::TOKEN_KEY] = 'token';
        $this->runApp('PATCH', '/todo/not-exists', [
            'name' => 'Test Name 1',
            'description' => 'Test message 1',
            'due_at' => '2019-09-10 10:00:00',
            '_token' => 'token'
        ]);
    }
}