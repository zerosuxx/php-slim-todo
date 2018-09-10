<?php

namespace TodoApp;

use PDO;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use TodoApp\Action\AddAction;
use TodoApp\Action\AddViewAction;
use TodoApp\Action\CompleteAction;
use TodoApp\Action\DeleteAction;
use TodoApp\Action\EditAction;
use TodoApp\Action\EditViewAction;
use TodoApp\Action\HealthCheckAction;
use TodoApp\Action\IndexViewAction;
use TodoApp\Dao\TodosDao;
use TodoApp\Form\TodoForm;
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
        $container[AddViewAction::class] = function (ContainerInterface $container) {
            return new AddViewAction($container->get('view'), new CSRFTokenValidator());
        };
        $container[AddAction::class] = function (ContainerInterface $container) {
            return new AddAction($container->get(TodosDao::class), new TodoForm());
        };
        $container[EditViewAction::class] = function (ContainerInterface $container) {
            return new EditViewAction($container->get(TodosDao::class), $container->get('view'), new CSRFTokenValidator());
        };
        $container[EditAction::class] = function (ContainerInterface $container) {
            return new EditAction($container->get(TodosDao::class), new TodoForm());
        };
        $container[CompleteAction::class] = function (ContainerInterface $container) {
            return new CompleteAction($container->get(TodosDao::class));
        };
        $container[DeleteAction::class] = function (ContainerInterface $container) {
            return new DeleteAction($container->get(TodosDao::class));
        };
    }

    public function routes(App $app)
    {
        $app->get('/healthcheck', HealthCheckAction::class);
        $app->get('/todos', IndexViewAction::class);
        $app->get('/todo/add', AddViewAction::class);
        $app->post('/todo/add', AddAction::class);
        $app->get('/todo/edit/{id}', EditViewAction::class);
        $app->post('/todo/edit/{id}', EditAction::class);
        $app->post('/todo/complete/{id}', CompleteAction::class);
        $app->post('/todo/delete/{id}', DeleteAction::class);
    }
}