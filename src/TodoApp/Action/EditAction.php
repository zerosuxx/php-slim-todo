<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApp\Dao\TodosDao;
use TodoApp\Form\TodoForm;

/**
 * Class EditAction
 * @package TodoApp\Action
 */
class EditAction
{
    /**
     * @var TodosDao
     */
    private $dao;
    /**
     * @var TodoForm
     */
    private $form;

    public function __construct(TodosDao $dao, TodoForm $form)
    {
        $this->dao = $dao;
        $this->form = $form;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {

        if ($this->form->handle($request)->isValid()) {
            $todo = $this->dao->getTodo($args['id']);

            $data = $this->form->handle($request)->getData();
            $data['id'] = $todo->getId();
            $data['status'] = $todo->getStatus();

            $todo = $this->dao->createTodoFromArray($data);
            $this->dao->updateTodo($todo);
        } else {
            $_SESSION['errors'] = $this->form->getErrors();
        }

        return $response->withRedirect('/todos', 301);
    }
}