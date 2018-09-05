<?php

namespace Test;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class SlimTestCase
 * @package Test
 */
class SlimTestCase extends TestCase
{
    /**
     * @var App
     */
    private $app;

    /**
     * @return App
     */
    protected function getApp(): App
    {
        if(null === $this->app) {
            $this->app = require APP_ROOT . '/config/app.php';
        }
        return $this->app;
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function getService($name)
    {
        return $this->getApp()->getContainer()->get($name);
    }

    /**
     * @param string $name [optional] default: PDO
     * @return PDO
     */
    protected function getPDO($name = 'PDO'): PDO
    {
        return $this->getService($name);
    }

    /**
     * @param string $table
     * @return bool|PDOStatement
     */
    protected function truncateTable($table)
    {
        return $this->getPDO()->query('TRUNCATE TABLE ' . $table);
    }

    /**
     * @param string $requestMethod
     * @param string $requestUri
     * @param array|null $requestData
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    protected function runApp($requestMethod, $requestUri, array $requestData = null)
    {
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );
        $request = Request::createFromEnvironment($environment);
        if (null !== $requestData) {
            $request = $request->withParsedBody($requestData);
        }
        $app = $this->getApp();
        $container = $app->getContainer();
        unset($container['errorHandler']);
        unset($container['phpErrorHandler']);
        return $app->process($request, new Response());
    }
}