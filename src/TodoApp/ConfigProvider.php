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
        $this->config($app->getContainer());
        $this->dependencies($app->getContainer());
        $this->routes($app);
    }

    public function config(ContainerInterface $container)
    {
        $container['settings']['displayErrorDetails'] = true;
    }

    public function dependencies(ContainerInterface $container)
    {
        $container['PDO'] = function () {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('DB_HOST'), getenv('DB_NAME'));
            $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        };
        $container[HealthCheckAction::class] = function (ContainerInterface $container) {
            return new HealthCheckAction($container->get('PDO'));
        };
    }

    public function routes(App $app)
    {
        $app->get('/healthcheck', HealthCheckAction::class);
    }
}