<?php

namespace TodoApp\Entity;

/**
 * Class Todo
 * @package TodoApp\Entity
 */
class Todo
{
    private $name;

    public function __construct(string $name)
    {

        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }


}