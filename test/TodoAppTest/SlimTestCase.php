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
    private static $globalApp = null;

    /**
     * @var App
     */
    private $app;

    /**
     * @var bool
     */
    private $slimErrorHandlerDisabled = true;

    /**
     * @param App $app
     * @return void
     */
    public static function setGlobalApp(App $app)
    {
        self::$globalApp = $app;
    }

    /**
     * @return App
     */
    private static function getGlobalApp()
    {
        if (null === self::$globalApp) {
            $message = sprintf(
                'Global Slim Instance is not set, use the %s method to set it up',
                __CLASS__ . '::setGlobalApp'
            );
            throw new \RuntimeException($message);
        }
        return self::$globalApp;
    }

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
            $this->app = self::getGlobalApp();
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
        if ($this->slimErrorHandlerDisabled) {
            unset($container['errorHandler']);
            unset($container['phpErrorHandler']);
        }
        return $app->process($request, new Response());
    }
}