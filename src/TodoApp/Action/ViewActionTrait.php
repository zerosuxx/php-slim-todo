<?php

namespace TodoApp\Action;

use Zero\Form\Validator\CSRFTokenValidator;
use Zero\Storage\StorageInterface;

trait ViewActionTrait
{
    private function getTemplateVars(CSRFTokenValidator $csrf, StorageInterface $storage) {
        $data = $storage->consume('data', []);
        $data['token'] = $csrf->getToken();
        return [
            'data' => $data,
            'errors' => $storage->consume('errors', [])
        ];
    }
}