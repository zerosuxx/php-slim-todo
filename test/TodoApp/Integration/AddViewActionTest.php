<?php

namespace Test\TodoApp\Integration;

use Test\TodoApp\TodoAppTestCase;

class AddViewActionTest extends TodoAppTestCase
{
    /**
     * @test
     */
    public function callsAddPage_Returns200()
    {
        $response = $this->runApp('GET', '/todo');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function callsAddPage_GivenErrorsAndData_Returns200WithErrorsAndData()
    {
        $storage = $this->loadArrayStorageToSession();
        $storage->set('errors', ['name' => 'Invalid data']);
        $storage->set('data', ['description' => 'Test desc']);
        $response = $this->runApp('GET', '/todo');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Invalid data', (string)$response->getBody());
        $this->assertContains('Test desc', (string)$response->getBody());
        $this->assertFalse($storage->has('errors'));
        $this->assertFalse($storage->has('data'));
    }
}