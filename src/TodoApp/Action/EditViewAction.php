<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use TodoApp\Dao\TodosDao;
use Zero\Form\Validator\CSRFTokenValidator;

class EditViewAction
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

    public function __invoke(Request $request, Response $response, array $args)
    {
        $id = (int)$args['id'];
        $todo = $this->dao->getTodo($id);
        return $this->view->render($response, 'edit.html.twig', ['action' => '/todo/edit/' . $id]);
    }
}