<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use TodoApp\Storage\StorageInterface;
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
    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(Twig $view, CSRFTokenValidator $csrf, StorageInterface $storage)
    {
        $this->view = $view;
        $this->csrf = $csrf;
        $this->storage = $storage;
    }

    public function __invoke(Request $request, Response $response)
    {
        $data = $this->storage->consume('data', []);
        $errors = $this->storage->consume('errors', []);
        $data['token'] = $this->csrf->getToken();
        return $this->view->render($response, 'add.html.twig', [
            'data' => $data,
            'errors' => $errors
        ]);
    }
}