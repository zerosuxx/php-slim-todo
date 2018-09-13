<?php

namespace Test\TodoApp;

use DateTime;
use Slim\App;
use SlimSkeleton\AppBuilder;
use Test\AbstractSlimTestCase;
use TodoApp\ConfigProvider;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;
use Zero\Form\Validator\CSRFTokenValidator;
use Zero\Form\Validator\ValidationException;
use Zero\Storage\ArrayStorage;
use Zero\Storage\StorageInterface;

/**
 * Class TodoAppTestCase
 */
class TodoAppTestCase extends AbstractSlimTestCase
{
    /**
     * @var TodosDao
     */
    protected $todosDao;

    protected function setUp()
    {
        parent::setUp();
        $this->truncateTable('todos');
        $this->todosDao = new TodosDao($this->getPDO());
    }

    /**
     * @param AppBuilder $appBuilder
     */
    protected function addProvider(AppBuilder $appBuilder)
    {
        $appBuilder->addProvider(new ConfigProvider());
    }

    /**
     * @param App $app
     */
    protected function initializeApp(App $app)
    {
        parent::initializeApp($app);
        $csrfMock = $this->createMock(CSRFTokenValidator::class);
        $csrfMock->method('validate')
            ->willReturnCallback(function ($token) {
                if ($token !== 'token') {
                    throw new ValidationException('Token mismatch');
                }
            });

        $container = $app->getContainer();
        $this->mockService($container, 'csrf', $csrfMock);
        $this->mockService($container, 'session', new ArrayStorage());
    }

    protected function getSession(): StorageInterface
    {
        return $this->getService('session');
    }

    protected function buildTodo(
        $name,
        $description,
        DateTime $dueAt = null,
        $status = Todo::STATUS_INCOMPLETE,
        $id = null
    ): Todo {
        return new Todo($name, $description, $dueAt ?: new \DateTime(), $status, $id);
    }
}