<?php

namespace Test\TodoApp\Integration;

use Test\TodoApp\TodoAppTestCase;

class TodosViewActionTest extends TodoAppTestCase
{
    /**
     * @test
     */
    public function callsListPage_Returns200WithTodos()
    {
        $savedTodo = $this->todosDao->saveTodo($this->buildTodo('Test Name', 'Test message'));
        $savedTodo2 = $this->todosDao->saveTodo($this->buildTodo('Test Name2', 'Test message2'));
        $response = $this->runApp('GET', '/todos');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains($savedTodo->getName(), (string)$response->getBody());
        $this->assertContains($savedTodo->getDescription(), (string)$response->getBody());
        $this->assertContains($savedTodo->getDueAtTimestamp(), (string)$response->getBody());
        $this->assertContains($savedTodo2->getName(), (string)$response->getBody());
        $this->assertContains($savedTodo2->getDescription(), (string)$response->getBody());
        $this->assertContains($savedTodo2->getDueAtTimestamp(), (string)$response->getBody());
    }
}