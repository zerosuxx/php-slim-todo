<?php

namespace TodoApp;

use PDO;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use TodoApp\Action\AddAction;
use TodoApp\Action\EditAction;
use TodoApp\Action\HealthCheckAction;
use TodoApp\Action\IndexViewAction;
use TodoApp\Dao\TodosDao;
use Zero\Form\Form;
use Zero\Form\Validator\CSRFTokenValidator;

/**
 * Class ConfigProvider
 */
class ConfigProvider
{
    public function __invoke(App $app)
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
        $container['pdo'] = function () {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', getenv('DB_HOST'), getenv('DB_NAME'));
            $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        };
        $container['view'] = function (ContainerInterface $container) {
            $view = new Twig(__DIR__ . '/templates', [
                'cache' => false
            ]);

            $router = $container->get('router');
            $uri = $container->get('request')->getUri();
            $view->addExtension(new TwigExtension($router, $uri));
            return $view;
        };
        $container[TodosDao::class] = function (ContainerInterface $container) {
            return new TodosDao($container->get('pdo'));
        };
        $container[HealthCheckAction::class] = function (ContainerInterface $container) {
            return new HealthCheckAction($container->get('pdo'));
        };
        $container[IndexViewAction::class] = function (ContainerInterface $container) {
            return new IndexViewAction($container->get(TodosDao::class), $container->get('view'));
        };
        $container[AddAction::class] = function (ContainerInterface $container) {
            return new AddAction($container->get(TodosDao::class), new Form());
        };
        $container[EditAction::class] = function (ContainerInterface $container) {
            return new EditAction($container->get(TodosDao::class), new Form());
        };
    }

    public function routes(App $app)
    {
        $app->get('/healthcheck', HealthCheckAction::class);
        $app->get('/todos', IndexViewAction::class);
        $app->post('/todo/add', AddAction::class);
        $app->post('/todo/edit/{id}', \TodoApp\Action\EditAction::class);
    }
}