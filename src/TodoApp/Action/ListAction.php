<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use TodoApp\Dao\TodosDao;

/**
 * Class ListAction
 * @package TodoApp\Action
 */
class ListAction
{
    /**
     * @var TodosDao
     */
    private $dao;
    /**
     * @var Twig
     */
    private $view;

    public function __construct(TodosDao $dao, Twig $view)
    {
        $this->dao = $dao;
        $this->view = $view;
    }

    public function __invoke(Request $request, Response $response)
    {
        return $this->view->render($response, 'list.html.twig', ['todos' => $this->dao->getTodos()]);
    }
}