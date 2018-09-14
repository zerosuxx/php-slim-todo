<?php

namespace TodoApp\Action;

use Zero\Form\Validator\CSRFTokenValidator;
use Zero\Storage\StorageInterface;

trait ViewActionTrait
{
    private function getTemplateVars(CSRFTokenValidator $csrf, StorageInterface $storage) {
        $formFields = $storage->pop('formFields', []);
        $data['token'] = $csrf->getToken();
        return [
            'formFields' => $formFields,
            'errors' => $storage->pop('errors', [])
        ];
    }
}