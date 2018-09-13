<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use TodoApp\Dao\TodosDao;
use Zero\Form\Validator\CSRFTokenValidator;

class TodosViewAction
{
    /**
     * @var TodosDao
     */
    private $dao;

    /**
     * @var Twig
     */
    private $view;
    /**
     * @var CSRFTokenValidator
     */
    private $csrf;

    public function __construct(TodosDao $dao, Twig $view, CSRFTokenValidator $csrf)
    {
        $this->dao = $dao;
        $this->view = $view;
        $this->csrf = $csrf;
    }

    public function __invoke(Request $request, Response $response)
    {
        return $this->view->render($response, 'todos.html.twig', [
            'todos' => $this->dao->getTodos(),
            'token' => $this->csrf->getToken()
        ]);
    }
}