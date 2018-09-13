<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApp\Dao\TodosDao;
use TodoApp\Form\TodoForm;
use Zero\Storage\StorageInterface;

/**
 * Class EditAction
 * @package TodoApp\Action
 */
class EditAction
{
    use FormActionTrait;
    /**
     * @var TodosDao
     */
    private $todosDao;
    /**
     * @var TodoForm
     */
    private $form;
    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(TodosDao $dao, TodoForm $form, StorageInterface $storage)
    {
        $this->todosDao = $dao;
        $this->form = $form;
        $this->storage = $storage;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        return $this->handle($this->form, $this->storage, $request, $response, $args);
    }

    protected function handleValidData(array $data, array $args)
    {
        $todo = $this->todosDao->getTodo((int)$args['id']);

        $data['id'] = $todo->getId();
        $data['status'] = $todo->getStatus();

        $todo = $this->todosDao->createTodoFromArray($data);
        $this->todosDao->updateTodo($todo);
    }
}