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

        return $this->createTodoFromArray($todoData);
    }

    /**
     * @return Todo[]
     */
    public function getTodos()
    {
        $statement = $this->pdo->query('SELECT id, name, description, status, due_at FROM todos');
        $todos = [];
        while ($todoData = $statement->fetch(PDO::FETCH_ASSOC)) {
            $todos[] = $this->createTodoFromArray($todoData);
        }
        return $todos;
    }

    public function saveTodo(Todo $todo): Todo
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO todos (name, description, status, due_at) VALUES (:name, :description, :status, :due_at)"
        );
        $statement->execute([
            'name' => $todo->getName(),
            'description' => $todo->getDescription(),
            'status' => $todo->getStatus(),
            'due_at' => $todo->getDueAt()->format('Y-m-d H:i:s'),
        ]);
        $id = $this->pdo->lastInsertId();
        return new Todo(
            $todo->getName(),
            $todo->getDescription(),
            $todo->getStatus(),
            $todo->getDueAt(),
            $id
        );
    }

    private function createTodoFromArray(array $todoData): Todo
    {
        return new Todo(
            $todoData['name'],
            $todoData['description'],
            $todoData['status'],
            new \DateTime($todoData['due_at']),
            $todoData['id']
        );
    }
}