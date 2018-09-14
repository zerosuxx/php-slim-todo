<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use TodoApp\Dao\TodosDao;
use TodoApp\Form\TodoForm;
use Zero\Storage\StorageInterface;

abstract class AbstractFormAction
{
    /**
     * @var TodosDao
     */
    protected $todosDao;
    /**
     * @var TodoForm
     */
    protected $form;
    /**
     * @var StorageInterface
     */
    protected $storage;

    public function __construct(TodosDao $dao, TodoForm $form, StorageInterface $storage)
    {
        $this->todosDao = $dao;
        $this->form = $form;
        $this->storage = $storage;
    }

    public function __invoke(Request $request, Response $response, array $args = [])
    {
        if ($this->form->handle($request)->isValid()) {
            $this->handleValidData($this->form->getData(), $args);
            return $response->withRedirect('/todos', 301);
        } else {
            $this->storage->set('errors', $this->form->getErrors());
            $validData = $this->form->getValidData();
            unset($validData['_token']);
            $this->storage->set('data', $validData);
            return $response->withRedirect($request->getHeaderLine('referer'), 301);
        }
    }

    abstract protected function handleValidData(array $data, array $args = []);
}