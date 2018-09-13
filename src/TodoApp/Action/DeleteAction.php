<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApp\Dao\TodosDao;
use Zero\Form\Validator\CSRFTokenValidator;

/**
 * Class DeleteAction
 * @package TodoApp\Action
 */
class DeleteAction
{
    /**
     * @var TodosDao
     */
    private $dao;
    /**
     * @var CSRFTokenValidator
     */
    private $csrf;

    public function __construct(TodosDao $dao, CSRFTokenValidator $csrf)
    {
        $this->dao = $dao;
        $this->csrf = $csrf;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $this->csrf->validate($request->getParsedBodyParam('_token'));
        $id = (int)$args['id'];
        $todo = $this->dao->getTodo($id);
        $this->dao->deleteTodo($todo);

        return $response->withRedirect('/todos', 301);
    }
}