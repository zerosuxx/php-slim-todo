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
    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function __invoke(Request $request, Response $response)
    {
        $this->pdo->query('SELECT 1');
        $response->getBody()->write('OK');
        return $response->withStatus(200);
    }
}