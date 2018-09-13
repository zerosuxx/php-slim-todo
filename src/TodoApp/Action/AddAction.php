<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;
use TodoApp\Form\TodoForm;
use TodoApp\Storage\StorageInterface;

/**
 * Class AddAction
 * @package TodoApp\Action
 */
class AddAction
{
    /**
     * @var TodosDao
     */
    private $dao;
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
        $this->dao = $dao;
        $this->form = $form;
        $this->storage = $storage;
    }

    public function __invoke(Request $request, Response $response)
    {
        if ($this->form->handle($request)->isValid()) {
            $data = $this->form->getData();
            $data['status'] = Todo::STATUS_INCOMPLETE;
            $todo = $this->dao->createTodoFromArray($data);
            $this->dao->saveTodo($todo);
            return $response->withRedirect('/todos', 301);
        } else {
            $this->storage->set('errors', $this->form->getErrors());
            $this->storage->set('data', $this->form->getValidData());
            return $response->withRedirect($request->getHeaderLine('referer'), 301);
        }
    }
}