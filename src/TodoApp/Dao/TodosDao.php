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
        $statement->execute($this->createTodoArrayFromTodo($todo));
        $data = $this->createTodoArrayFromTodo($todo);
        $data['id'] = $this->pdo->lastInsertId();
        return $this->createTodoFromArray($data);
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
        return $statement->execute($this->createTodoArrayFromTodo($todo));
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
            new \DateTime($todoData['due_at']),
            $todoData['id']
        );
    }

    /**
     * @param Todo $todo
     * @return array
     */
    private function createTodoArrayFromTodo(Todo $todo)
    {
        $todoData = [
            'name' => $todo->getName(),
            'description' => $todo->getDescription(),
            'status' => $todo->getStatus(),
            'due_at' => $todo->getDueAt()->format('Y-m-d H:i:s')
        ];
        $id = $todo->getId();
        if ($id) {
            $todoData['id'] = $id;
        }
        return $todoData;
    }

    public function completeTodo(int $id)
    {
        $todo = $this->getTodo($id);
        $todoArray = $this->createTodoArrayFromTodo($todo);
        $todoArray['status'] = 'complete';
        $modifiedTodo = $this->createTodoFromArray($todoArray);
        return $this->updateTodo($modifiedTodo);
    }
}