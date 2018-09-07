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
    /**
     * @var string
     */
    private $status;

    public function __construct(string $name, string $description, string $status)
    {
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
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

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }


}