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
    const STATUS_COMPLETE = 'complete';

    /**
     * @var string
     */
    const STATUS_INCOMPLETE = 'incomplete';

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

    public function __construct(string $name, string $description, DateTime $dueAt, string $status = self::STATUS_INCOMPLETE, int $id = null)
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
     * @return string
     */
    public function getDueAtTimestamp(): string
    {
        return $this->dueAt->format('Y-m-d H:i:s');
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return self
     */
    public function withId(int $id): self
    {
        $new = clone $this;
        $new->id = $id;
        return $new;
    }

    /**
     * @param string $status
     * @return self
     */
    public function withStatus(string $status): self
    {
        $new = clone $this;
        $new->status = $status;
        return $new;
    }

}