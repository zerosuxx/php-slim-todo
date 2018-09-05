<?php

namespace TodoApp;

use PDO;
use Psr\Container\ContainerInterface;
use Slim\App;
use TodoApp\Action\HealthCheckAction;

/**
 * Class ConfigProvider
 */
class ConfigProvider
{
    public function __construct(App $app)
    {
        $this->routes($app);
        $this->dependecies($app->getContainer());
    }

    public function routes(App $app)
    {
        $app->get('/healthcheck', HealthCheckAction::class);
    }

    public function dependecies(ContainerInterface $container)
    {
        $container['PDO'] = function () {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('DB_HOST'), getenv('DB_NAME'));
            return new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
        };
    }
}