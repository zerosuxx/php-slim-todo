<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApp\Dao\TodosDao;

/**
 * Class CompleteAction
 * @package TodoApp\Action
 */
class CompleteAction
{
    /**
     * @var TodosDao
     */
    private $dao;

    public function __construct(TodosDao $dao)
    {
        $this->dao = $dao;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $id = (int)$args['id'];
        $todo = $this->dao->getTodo($id);
        $this->dao->completeTodo($todo);

        return $response->withRedirect('/todos', 301);
    }
}