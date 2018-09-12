<?php

namespace Test\TodoApp\Integration;

use Test\TodoApp\TodoAppTestCase;

class HealthCheckActionTest extends TodoAppTestCase
{

    protected function setUp()
    {

    }

    /**
     * @test
     */
    public function callsHealthCheckPage_Returns200OK()
    {
        $response = $this->runApp('GET', '/healthcheck');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', (string)$response->getBody());
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function callsHealthCheckPage_WithBadConnectionData_Returns500()
    {
        $this->disableSlimErrorHandler(false);
        putenv('DB_USER=""');
        $response = $this->runApp('GET', '/healthcheck');
        $this->assertEquals(500, $response->getStatusCode());
    }
}