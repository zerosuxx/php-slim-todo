<?php

namespace Test\TodoAppTest\Integration\Dao;

use Test\TodoAppTest\TodoAppTestCase;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;

/**
 * Class TodosDaoTest
 */
class TodosDaoTest extends TodoAppTestCase
{
    /**
     * @test
     */
    public function getTodo_ReturnsOneTodoFromDb()
    {
        $this->truncateTable('todos');
        $this->getPDO()->query(
            "INSERT INTO todos (name, description, status, due_at) VALUES ('Test name', 'test desc', 'incomplete', '2018-09-07 10:00:00')"
        );
        $dao = new TodosDao($this->getPDO());
        $todo = $dao->getTodo(1);
        $this->assertInstanceOf(Todo::class, $todo);
        $this->assertEquals(1, $todo->getId());
        $this->assertEquals('Test name', $todo->getName());
        $this->assertEquals('test desc', $todo->getDescription());
        $this->assertEquals('incomplete', $todo->getStatus());
        $this->assertEquals('2018-09-07 10:00:00', $todo->getDueAt()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function getTodo_GivenEmptyDatabase_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->truncateTable('todos');
        $dao = new TodosDao($this->getPDO());
        $dao->getTodo(999);
    }

    /**
     * @test
     */
    public function saveTodo_EmptyDatabase_ReturnsInsertedTodo()
    {
        $this->truncateTable('todos');

        $todo = new Todo('name', 'desc', 'incomplete', new \DateTime('2018-09-07 10:00:00'));
        $dao = new TodosDao($this->getPDO());

        $savedTodo = $dao->saveTodo($todo);

        $todoFromDb = $dao->getTodo(1);

        $this->assertInstanceOf(Todo::class, $savedTodo);
        $this->assertEquals(1, $savedTodo->getId());
        $this->assertEquals($todoFromDb, $savedTodo);
    }
}