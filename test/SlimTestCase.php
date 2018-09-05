<?php

namespace Test;

use PDO;
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
     * @return App
     */
    public function getApp(): App
    {
        return require APP_ROOT . '/config/app.php';
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getService($name)
    {
        return $this->getApp()->getContainer()->get($name);
    }

    /**
     * @param string $name [optional] default: PDO
     * @return PDO
     */
    public function getPDO($name = 'PDO'): PDO
    {
        return $this->getService($name);
    }

    /**
     * @param string $requestMethod
     * @param string $requestUri
     * @param array|null $requestData
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function runApp($requestMethod, $requestUri, $requestData = null)
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