<?php

namespace TodoApp\Entity;

/**
 * Class Todo
 * @package TodoApp\Entity
 */
class Todo
{
    private $name;

    /**
     * @var string
     */
    private $description;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


}