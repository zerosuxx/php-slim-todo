<?php

namespace TodoApp\Form;

use Zero\Form\Filter\StringFilter;
use Zero\Form\Form;
use Zero\Form\Validator\CSRFTokenValidator;
use Zero\Form\Validator\DateTimeValidator;
use Zero\Form\Validator\EmptyValidator;
use Zero\Form\Validator\ValidatorChain;

/**
 * Class TodoForm
 * @package TodoApp\Form
 */
class TodoForm extends Form
{
    public function __construct()
    {
        $dateValidator = new ValidatorChain();
        $dateValidator
            ->add(new EmptyValidator('Due At'))
            ->add(new DateTimeValidator());

        $this->input('name', new StringFilter(), new EmptyValidator('Name'));
        $this->input('description', new StringFilter(), new EmptyValidator('Description'));
        $this->input('due_at', new StringFilter(), $dateValidator);
        $this->input('_token', new StringFilter(), new CSRFTokenValidator());
    }
}