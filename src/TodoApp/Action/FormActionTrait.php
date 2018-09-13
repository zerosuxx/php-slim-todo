<?php

namespace TodoApp\Action;

use Slim\Http\Request;
use Slim\Http\Response;
use Zero\Form\Form;
use Zero\Storage\StorageInterface;

trait FormActionTrait
{
    private function handle(Form $form, StorageInterface $storage, Request $request, Response $response, array $args = [])
    {
        if ($form->handle($request)->isValid()) {
            $this->handleValidData($form->getData(), $args);
            return $response->withRedirect('/todos', 301);
        } else {
            $storage->set('errors', $form->getErrors());
            $validData = $form->getValidData();
            unset($validData['_token']);
            $storage->set('data', $validData);
            return $response->withRedirect($request->getHeaderLine('referer'), 301);
        }
    }

    abstract protected function handleValidData(array $data, array $args = []);
}