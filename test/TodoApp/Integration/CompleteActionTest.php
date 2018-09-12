<?php

namespace Test\TodoApp\Integration;

use Test\TodoApp\TodoAppTestCase;
use TodoApp\Entity\Todo;

class CompleteActionTest extends TodoAppTestCase
{

    /**
     * @test
     */
    public function callsCompletePage_GivenValidData_Returns301()
    {
        $this->todosDao->saveTodo(new Todo('Test Name', 'Test message', 'incomplete', new \DateTime()));
        $response = $this->runApp('COMPLETE', '/todo/1');

        $this->assertEquals(301, $response->getStatusCode());

        $todo = $this->todosDao->getTodo(1);
        $this->assertEquals('complete', $todo->getStatus());
    }

    /**
     * @test
     */
    public function callsCompletePage_WithNotExistsTodo_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->runApp('COMPLETE', '/todo/not-exists');
    }
}