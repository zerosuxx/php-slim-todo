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
use TodoApp\Action\TodosViewAction;
use TodoApp\Dao\TodosDao;
use TodoApp\Form\TodoForm;
use TodoApp\Storage\SessionStorage;
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
        $container['session'] = function () {
            return new SessionStorage();
        };
        $container[TodosDao::class] = function (ContainerInterface $container) {
            return new TodosDao($container->get('pdo'));
        };
        $container[HealthCheckAction::class] = function (ContainerInterface $container) {
            return new HealthCheckAction($container->get('pdo'));
        };
        $container[TodosViewAction::class] = function (ContainerInterface $container) {
            return new TodosViewAction($container->get(TodosDao::class), $container->get('view'));
        };
        $container[AddViewAction::class] = function (ContainerInterface $container) {
            return new AddViewAction($container->get('view'), new CSRFTokenValidator(), $container->get('session'));
        };
        $container[AddAction::class] = function (ContainerInterface $container) {
            return new AddAction($container->get(TodosDao::class), new TodoForm(), $container->get('session'));
        };
        $container[EditViewAction::class] = function (ContainerInterface $container) {
            return new EditViewAction(
                $container->get(TodosDao::class),
                $container->get('view'),
                new CSRFTokenValidator(),
                $container->get('session')
            );
        };
        $container[EditAction::class] = function (ContainerInterface $container) {
            return new EditAction($container->get(TodosDao::class), new TodoForm(), $container->get('session'));
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
        $app->get('/todos', TodosViewAction::class);
        $app->get('/todo', AddViewAction::class);
        $app->post('/todo', AddAction::class);
        $app->get('/todo/{id}', EditViewAction::class);
        $app->patch('/todo/{id}', EditAction::class);
        $app->map(['complete'], '/todo/{id}', CompleteAction::class);
        $app->delete('/todo/{id}', DeleteAction::class);
    }
}