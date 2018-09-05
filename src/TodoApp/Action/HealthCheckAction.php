<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class HealthCheckAction
 * @package TodoApp\Action
 */
class HealthCheckAction
{
    public function __invoke(Request $request, Response $response)
    {
        return $response->withStatus(200);
    }
}