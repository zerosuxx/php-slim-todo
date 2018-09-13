<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use Zero\Form\Validator\CSRFTokenValidator;
use Zero\Storage\StorageInterface;

class AddViewAction
{
    use ViewActionTrait;
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
        return $this->view->render($response, 'add.html.twig', $this->getTemplateVars($this->csrf, $this->storage));
    }
}