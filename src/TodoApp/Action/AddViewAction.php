<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use Zero\Form\Validator\CSRFTokenValidator;

class AddViewAction
{
    /**
     * @var Twig
     */
    private $view;

    /**
     * @var CSRFTokenValidator
     */
    private $csrf;

    public function __construct(Twig $view, CSRFTokenValidator $csrf)
    {
        $this->view = $view;
        $this->csrf = $csrf;
    }

    public function __invoke(Request $request, Response $response)
    {
        $data = $this->consumeSessionData('data', []);
        $errors = $this->consumeSessionData('errors', []);
        $data['token'] = $this->csrf->getToken();
        return $this->view->render($response, 'add.html.twig', [
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