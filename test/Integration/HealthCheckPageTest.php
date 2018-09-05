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
    }
}