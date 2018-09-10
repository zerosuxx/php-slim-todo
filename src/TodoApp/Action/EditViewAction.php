<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use TodoApp\ConfigProvider;
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
        $data = $this->consumeSessionData('data', []) + [
            'name' => $todo->getName(),
            'description' => $todo->getDescription(),
            'due_at' => $todo->getDueAt()->format(ConfigProvider::FORMAT_DATETIME),
        ];
        $data['token'] = $this->csrf->getToken();
        $errors = $this->consumeSessionData('errors', []);
        return $this->view->render($response, 'edit.html.twig', [
            'action' => '/todo/edit/' . $id,
            'data' => $data,
            'errors' => $errors
        ]);
    }

    /**
     * @param string $key
     * @param mixed $default [optional] default: null
     * @return array|mixed
     */
    private function consumeSessionData(string $key, $default = null) {
        if(!empty($_SESSION[$key])) {
            $data = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $data;
        }
        return $default;
    }
}