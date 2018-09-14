<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use TodoApp\Dao\TodosDao;
use Zero\Form\Validator\CSRFTokenValidator;
use Zero\Storage\StorageInterface;

class EditViewAction
{
    use ViewActionTrait;
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
    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(TodosDao $dao, Twig $view, CSRFTokenValidator $csrf, StorageInterface $storage)
    {
        $this->dao = $dao;
        $this->view = $view;
        $this->csrf = $csrf;
        $this->storage = $storage;
    }

    public function __invoke(Request $request, Response $response, array $args)
    {
        $todo = $this->dao->getTodo((int)$args['id']);
        $vars = $this->getTemplateVars($this->csrf, $this->storage);
        $vars += [
            'id' => $todo->getId(),
            'method' => 'PATCH',
        ];
        $todoData = [
            'name' => $todo->getName(),
            'description' => $todo->getDescription(),
            'due_at' => $todo->getDueAtTimestamp(),
        ];
        $vars['formFields'] += $todoData;
        return $this->view->render($response, 'edit.html.twig', $vars);
    }
}