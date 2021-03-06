<?php

namespace Test\TodoApp\Integration;

use Test\TodoApp\TodoAppTestCase;

class AddActionTest extends TodoAppTestCase
{
    /**
     * @test
     */
    public function callsAddPage_GivenValidData_Returns301()
    {
        $response = $this->runApp('POST', '/todo', [
            'name' => 'Test Name',
            'description' => 'Test message',
            'due_at' => '2018-09-10 10:00:00',
            '_token' => 'token'
        ]);
        $this->assertEquals(301, $response->getStatusCode());

        $todo = $this->todosDao->getTodo(1);
        $this->assertEquals('Test Name', $todo->getName());
        $this->assertEquals('Test message', $todo->getDescription());
        $this->assertEquals('incomplete', $todo->getStatus());
        $this->assertEquals('2018-09-10 10:00:00', $todo->getDueAtTimestamp());
    }

    /**
     * @test
     */
    public function callsAddPage_GivenEmptyData_Returns301()
    {
        $storage = $this->getSession();
        $response = $this->runApp('POST', '/todo', []);

        $this->assertEquals(301, $response->getStatusCode());

        $todos = $this->todosDao->getTodos();
        $this->assertCount(0, $todos);
        $this->assertEquals([
            'name' => 'Name can not be empty',
            'description' => 'Description can not be empty',
            'due_at' => 'Due At can not be empty' . "\n" . 'Wrong datetime format',
            '_token' => 'Token mismatch'
        ], $storage->get('errors'));
    }
}