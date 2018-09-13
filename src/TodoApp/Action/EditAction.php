<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApp\Dao\TodosDao;
use TodoApp\Form\TodoForm;
use TodoApp\Storage\StorageInterface;

/**
 * Class EditAction
 * @package TodoApp\Action
 */
class EditAction
{
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
        if ($this->form->handle($request)->isValid()) {
            $todo = $this->todosDao->getTodo((int)$args['id']);

            $data = $this->form->handle($request)->getData();
            $data['id'] = $todo->getId();
            $data['status'] = $todo->getStatus();

            $todo = $this->todosDao->createTodoFromArray($data);
            $this->todosDao->updateTodo($todo);
            return $response->withRedirect('/todos', 301);
        } else {
            $this->storage->set('errors', $this->form->getErrors());
            $this->storage->set('data', $this->form->getValidData());
            return $response->withRedirect($request->getHeaderLine('referer'), 301);
        }
    }
}