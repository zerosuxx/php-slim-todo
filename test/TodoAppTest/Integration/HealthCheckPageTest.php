<?php

use Test\SlimTestCase;

/**
 * Class HealthCheckPageTest
 */
class HealthCheckPageTest extends SlimTestCase
{
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
    public function callsHealthCheckPage_WithBadConnectionData_Returns200OK()
    {
        $this->disableSlimErrorHandler(false);
        putenv('DB_USER=""');
        $response = $this->runApp('GET', '/healthcheck');
        $this->assertEquals(500, $response->getStatusCode());
    }
}