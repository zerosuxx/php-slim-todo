<?php

namespace Test\TodoApp\Integration;

use Test\TodoApp\TodoAppTestCase;
use TodoApp\Entity\Todo;

class EditViewActionTest extends TodoAppTestCase
{

    /**
     * @test
     */
    public function callsEditPage_Returns200()
    {
        $this->todosDao->saveTodo(new Todo('Test Name', 'Test message', 'incomplete', new \DateTime()));
        $response = $this->runApp('GET', '/todo/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('PATCH', (string)$response->getBody());
        $this->assertContains('Test Name', (string)$response->getBody());
        $this->assertContains('Test message', (string)$response->getBody());
        $this->assertContains('/todo/1', (string)$response->getBody());
    }

    /**
     * @test
     */
    public function callsEditPage_WithNotExistsTodo_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->runApp('GET', '/todo/not-exists');
    }

    /**
     * @test
     */
    public function callsEditPage_GivenErrorsAndData_Returns200WithErrorsAndData()
    {
        $this->todosDao->saveTodo(new Todo('Test Name', 'Test message', 'incomplete', new \DateTime()));
        $_SESSION['errors'] = ['name' => 'Invalid data'];
        $_SESSION['data'] = ['description' => 'Test desc'];
        $response = $this->runApp('GET', '/todo/1');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Invalid data', (string)$response->getBody());
        $this->assertContains('Test desc', (string)$response->getBody());
        $this->assertArrayNotHasKey('errors', $_SESSION);
        $this->assertArrayNotHasKey('data', $_SESSION);
    }
}