<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApp\Dao\TodosDao;
use TodoApp\Form\TodoForm;

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

    public function __construct(TodosDao $dao, TodoForm $form)
    {
        $this->dao = $dao;
        $this->form = $form;
    }

    public function __invoke(Request $request, Response $response)
    {
        if ($this->form->handle($request)->isValid()) {
            $data = $this->form->handle($request)->getData();
            $data['status'] = 'incomplete';

            $todo = $this->dao->createTodoFromArray($data);
            $this->dao->saveTodo($todo);
        } else {
            $_SESSION['errors'] = $this->form->getErrors();
        }

        return $response->withRedirect('/todos', 301);
    }
}