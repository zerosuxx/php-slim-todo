<?php

namespace TodoApp\Dao;

use DateTime;
use PDO;
use PDOStatement;
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

    /**
     * @param int $id
     * @return Todo
     */
    public function getTodo(int $id): Todo
    {
        $statement = $this->pdo->prepare('SELECT id, name, description, status, due_at FROM todos WHERE id = :id');
        $statement->execute([
            'id' => $id
        ]);
        $todoData = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$todoData) {
            throw new \InvalidArgumentException('Todo not found');
        }

        return $this->createTodoFromArray($todoData);
    }

    /**
     * @return Todo[]
     */
    public function getTodos()
    {
        $statement = $this->pdo->query("SELECT id, name, description, status, due_at FROM todos WHERE status = 'incomplete' ORDER BY due_at ASC");
        $todos = [];
        while ($todoData = $statement->fetch(PDO::FETCH_ASSOC)) {
            $todos[] = $this->createTodoFromArray($todoData);
        }
        return $todos;
    }

    /**
     * @param Todo $todo
     * @return Todo
     */
    public function saveTodo(Todo $todo): Todo
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO todos (name, description, status, due_at) VALUES (:name, :description, :status, :due_at)"
        );
        $this->bindTodo($statement, $todo)->execute();
        return $todo->withId($this->pdo->lastInsertId());
    }

    /**
     * @param Todo $todo
     * @return bool
     */
    public function updateTodo(Todo $todo)
    {
        $statement = $this->pdo->prepare(
            "UPDATE todos SET name = :name, description = :description, status = :status, due_at = :due_at WHERE id = :id"
        );
        return $this->bindTodo($statement, $todo)->execute();
    }

    /**
     * @param Todo $todo
     * @return Todo
     */
    public function completeTodo(Todo $todo)
    {
        $completedTodo = $todo->withStatus('complete');
        $this->updateTodo($completedTodo);
        return $completedTodo;
    }

    /**
     * @param Todo $todo
     * @return bool
     */
    public function deleteTodo(Todo $todo)
    {
        $statement = $this->pdo->prepare(
            "DELETE FROM todos WHERE id = :id"
        );
        return $statement->execute([
            'id' => $todo->getId()
        ]);
    }

    /**
     * @param PDOStatement $statement
     * @param Todo $todo
     * @return PDOStatement
     */
    private function bindTodo(PDOStatement $statement, Todo $todo)
    {
        $id = $todo->getId();
        if($id) {
            $statement->bindParam('id', $id, PDO::PARAM_INT);
        }
        $statement->bindParam('name', $todo->getName());
        $statement->bindParam('description', $todo->getDescription());
        $statement->bindParam('status', $todo->getStatus());
        $statement->bindParam('due_at', $todo->getDueAt()->format('Y-m-d H:i:s'));
        return $statement;
    }

    /**
     * @param array $todoData
     * @return Todo
     */
    private function createTodoFromArray(array $todoData): Todo
    {
        return new Todo(
            $todoData['name'],
            $todoData['description'],
            $todoData['status'],
            new DateTime($todoData['due_at']),
            $todoData['id']
        );
    }
}