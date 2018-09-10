<?php

namespace Test\TodoAppTest\Integration;

use Test\TodoAppTest\TodoAppTestCase;

class AddViewActionTest extends TodoAppTestCase
{
    /**
     * @test
     */
    public function callsAddPage_Returns200()
    {
        $response = $this->runApp('GET', '/todo/add');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function callsAddPage_GivenErrorsAndData_Returns200WithErrorsAndDatas()
    {
        $_SESSION['errors'] = ['name' => 'Invalid data'];
        $_SESSION['data'] = ['description' => 'Test desc'];
        $response = $this->runApp('GET', '/todo/add');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('Invalid data', (string)$response->getBody());
        $this->assertContains('Test desc', (string)$response->getBody());
        $this->assertArrayNotHasKey('errors', $_SESSION);
        $this->assertArrayNotHasKey('data', $_SESSION);
    }
}