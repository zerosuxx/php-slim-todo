<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use TodoApp\Dao\TodosDao;
use TodoApp\Storage\StorageInterface;
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
        $id = (int)$args['id'];
        $todo = $this->dao->getTodo($id);
        $data = $this->storage->consume('data', []);
        $data += [
            'name' => $todo->getName(),
            'description' => $todo->getDescription(),
            'due_at' => $todo->getDueAtTimestamp(),
        ];
        $data['token'] = $this->csrf->getToken();
        $errors = $this->storage->consume('errors', []);
        return $this->view->render($response, 'edit.html.twig', [
            'id' => $id,
            'method' => 'PATCH',
            'data' => $data,
            'errors' => $errors
        ]);
    }
}