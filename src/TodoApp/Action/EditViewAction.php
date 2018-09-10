<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use Zero\Form\Validator\CSRFTokenValidator;

class EditViewAction
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

    public function __invoke(Request $request, Response $response, array $args)
    {
        $id = (int)$args['id'];
        return $this->view->render($response, 'edit.html.twig', ['action' => '/todo/edit/' . $id]);
    }
}