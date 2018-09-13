<?php

namespace Test;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Container;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use SlimSkeleton\AppBuilder;

/**
 * Class AbstractSlimTestCase
 * @package Test
 */
abstract class AbstractSlimTestCase extends TestCase
{

    /**
     * @var App
     */
    private $app;

    /**
     * @var bool
     */
    private $slimErrorHandlerDisabled = true;

    /**
     * @param AppBuilder $appBuilder
     * @return void
     */
    abstract protected function addProvider(AppBuilder $appBuilder);

    protected function initializeApp(App $app) {

    }

    /**
     * @param $disable
     */
    protected function disableSlimErrorHandler($disable)
    {
        $this->slimErrorHandlerDisabled = $disable;
    }

    /**
     * @return App
     */
    protected function getApp(): App
    {
        if (null === $this->app) {
            $appBuilder = new AppBuilder();
            $this->addProvider($appBuilder);
            $this->app = $appBuilder->buildApp();
            $this->initializeApp($this->app);
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
     * @param string $name [optional] default: pdo
     * @return PDO
     */
    protected function getPDO($name = 'pdo'): PDO
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
        if ($this->slimErrorHandlerDisabled) {
            unset($container['errorHandler']);
            unset($container['phpErrorHandler']);
        }
        return $app->process($request, new Response());
    }

    /**
     * @param Container $container
     * @param $name
     * @param $mock
     * @param string $prefix [optional] default: original_
     */
    protected function mockService(Container $container, $name, $mock, $prefix = 'original_')
    {
        if (isset($container[$name])) {
            $service = $container[$name];
            unset($container[$name]);
            $container[$prefix . $name] = $service;
        }
        $container[$name] = $mock;
    }
}