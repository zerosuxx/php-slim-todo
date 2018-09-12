<?php

namespace Test\TodoApp\Integration;

use Test\TodoApp\TodoAppTestCase;
use TodoApp\Entity\Todo;

class TodosViewActionTest extends TodoAppTestCase
{
    /**
     * @test
     */
    public function callsListPage_Returns200WithTodos()
    {
        $savedTodo = $this->todosDao->saveTodo(new Todo('Test Name', 'test message', 'incomplete', new \DateTime()));
        $savedTodo2 = $this->todosDao->saveTodo(new Todo('Test Name2', 'test message2', 'incomplete', new \DateTime()));
        $response = $this->runApp('GET', '/todos');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains($savedTodo->getName(), (string)$response->getBody());
        $this->assertContains($savedTodo->getDescription(), (string)$response->getBody());
        $this->assertContains($savedTodo->getDueAt()->format('Y-m-d H:i:s'), (string)$response->getBody());
        $this->assertContains($savedTodo2->getName(), (string)$response->getBody());
        $this->assertContains($savedTodo2->getDescription(), (string)$response->getBody());
        $this->assertContains($savedTodo2->getDueAt()->format('Y-m-d H:i:s'), (string)$response->getBody());
    }
}