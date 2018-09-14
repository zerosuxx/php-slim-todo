<?php

namespace TodoApp\Action;

/**
 * Class EditAction
 * @package TodoApp\Action
 */
class EditAction extends AbstractFormAction
{

    protected function handleValidData(array $data, array $args = [])
    {
        $todo = $this->todosDao->getTodo((int)$args['id']);

        $data['id'] = $todo->getId();
        $data['status'] = $todo->getStatus();

        $todo = $this->todosDao->createTodoFromArray($data);
        $this->todosDao->updateTodo($todo);
    }
}