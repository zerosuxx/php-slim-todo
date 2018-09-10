<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApp\Dao\TodosDao;
use Zero\Form\Filter\StringFilter;
use Zero\Form\Form;
use Zero\Form\Validator\CSRFTokenValidator;
use Zero\Form\Validator\EmptyValidator;

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
     * @var Form
     */
    private $form;

    public function __construct(TodosDao $dao, Form $form)
    {
        $this->dao = $dao;
        $this->form = $form;
    }

    public function __invoke(Request $request, Response $response)
    {
        $this->form->input('name', new StringFilter(), new EmptyValidator('Name'));
        $this->form->input('description', new StringFilter(), new EmptyValidator('Description'));
        $this->form->input('due_at', new StringFilter(), new EmptyValidator('Due At'));
        $this->form->input('_token', new StringFilter(), new CSRFTokenValidator());

        $data = $this->form->handle($request)->getData();
        $data['status'] = 'incomplete';

        $todo = $this->dao->createTodoFromArray($data);
        $this->dao->saveTodo($todo);

        return $response->withRedirect('/todos', 301);
    }
}