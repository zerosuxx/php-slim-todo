<?php

namespace Test\TodoApp\Integration;

use DateTime;
use InvalidArgumentException;
use Test\TodoApp\TodoAppTestCase;
use TodoApp\Entity\Todo;

class DeleteActionTest extends TodoAppTestCase
{

    /**
     * @test
     */
    public function callsDeletePage_GivenValidData_Returns301()
    {
        $this->todosDao->saveTodo(new Todo('Test Name', 'Test message', 'incomplete', new DateTime()));
        $response = $this->runApp('DELETE', '/todo/1');

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
        $this->runApp('DELETE', '/todo/not-exists');
    }
}