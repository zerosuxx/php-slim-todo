<?php

namespace TodoApp\Dao;

use PDO;
use TodoApp\Entity\Todo;

/**
 * Class TodosDao
 * @package TodoApp\Dao
 */
class TodosDao
{

    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getTodo(int $id): Todo
    {
        $statement = $this->pdo->prepare('SELECT id, name, description, status, due_at FROM todos WHERE id = :id');
        $statement->execute(['id' => $id]);
        $todoData = $statement->fetch(PDO::FETCH_ASSOC);

        if(!$todoData) {
            throw new \InvalidArgumentException('Todo not found');
        }

        return new Todo(
            $todoData['name'],
            $todoData['description'],
            $todoData['status'],
            new \DateTime($todoData['due_at']),
            $todoData['id']
        );
    }
}