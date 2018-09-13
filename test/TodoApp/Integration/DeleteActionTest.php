<?php

namespace Test\TodoApp\Integration;

use InvalidArgumentException;
use Test\TodoApp\TodoAppTestCase;

class DeleteActionTest extends TodoAppTestCase
{

    /**
     * @test
     */
    public function callsDeletePage_GivenValidData_Returns301()
    {
        $this->todosDao->saveTodo($this->buildTodo('Test Name', 'Test message'));
        $response = $this->runApp('DELETE', '/todo/1', ['_token' => 'token']);

        $this->assertEquals(301, $response->getStatusCode());

        $todos = $this->todosDao->getTodos();
        $this->assertCount(0, $todos);
    }

    /**
     * @test
     */
    public function callsDeletePage_WithNotExistsTodo_ThrowException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->runApp('DELETE', '/todo/not-exists', ['_token' => 'token']);
    }
}