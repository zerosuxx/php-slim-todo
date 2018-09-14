<?php

namespace TodoApp\Action;

use TodoApp\Entity\Todo;

/**
 * Class AddAction
 * @package TodoApp\Action
 */
class AddAction extends AbstractFormAction
{
    protected function handleValidData(array $data, array $args = [])
    {
        $data['status'] = Todo::STATUS_INCOMPLETE;
        $todo = $this->todosDao->createTodoFromArray($data);
        $this->todosDao->saveTodo($todo);
    }
}