<?php

namespace TodoApp\Entity;

use DateTime;

/**
 * Class Todo
 * @package TodoApp\Entity
 */
class Todo
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $status;

    /**
     * @var DateTime
     */
    private $dueAt;

    /**
     * @var int
     */
    private $id;

    public function __construct(string $name, string $description, string $status, DateTime $dueAt, int $id = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->dueAt = $dueAt;
        $this->id = $id;
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

    /**
     * @return DateTime
     */
    public function getDueAt(): DateTime
    {
        return $this->dueAt;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

}