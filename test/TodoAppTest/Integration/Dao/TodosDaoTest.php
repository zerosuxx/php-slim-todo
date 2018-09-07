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
     * @var TodosDao
     */
    private $dao;

    protected function setUp()
    {
        $this->truncateTable('todos');
        $this->dao = new TodosDao($this->getPDO());
    }

    /**
     * @test
     */
    public function getTodo_ReturnsOneTodoFromDb()
    {
        $this->getPDO()->query(
            "INSERT INTO todos (name, description, status, due_at) VALUES ('Test name', 'test desc', 'incomplete', '2018-09-07 10:00:00')"
        );
        $todo = $this->dao->getTodo(1);
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
        $this->dao->getTodo(999);
    }

    /**
     * @test
     */
    public function saveTodo_EmptyDatabase_ReturnsInsertedTodo()
    {
        $todo = new Todo('name', 'desc', 'incomplete', new \DateTime('2018-09-07 10:00:00'));

        $savedTodo = $this->dao->saveTodo($todo);

        $todoFromDb = $this->dao->getTodo(1);

        $this->assertInstanceOf(Todo::class, $savedTodo);
        $this->assertEquals(1, $savedTodo->getId());
        $this->assertEquals($todoFromDb, $savedTodo);
    }

    /**
     * @test
     */
    public function getTodos_GivenExistingRecords_ReturnsTodos()
    {
        $records = [
            new Todo('name 1', 'desc 1', 'incomplete', new \DateTime('2018-09-07 10:00:00')),
            new Todo('name 2', 'desc 2', 'incomplete', new \DateTime('2018-09-07 10:00:00')),
            new Todo('name 3', 'desc 3', 'incomplete', new \DateTime('2018-09-07 10:00:00'))
        ];

        $savedRecords = [];
        foreach ($records as $record) {
            $savedRecords[] = $this->dao->saveTodo($record);
        }

        $todosFromDb = $this->dao->getTodos();

        $this->assertEquals($todosFromDb, $savedRecords);
    }

    /**
     * @test
     */
    public function getTodos_GivenExistingRecords_ReturnsInCompletedTodos()
    {
        $records = [
            new Todo('name 1', 'desc 1', 'incomplete', new \DateTime('2018-09-07 10:00:00')),
            new Todo('name 2', 'desc 2', 'incomplete', new \DateTime('2018-09-07 10:00:00')),
            new Todo('name 3', 'desc 3', 'complete', new \DateTime('2018-09-07 10:00:00'))
        ];

        $savedRecords = [];
        foreach ($records as $record) {
            $savedRecords[] = $this->dao->saveTodo($record);
        }

        $todosFromDb = $this->dao->getTodos();

        $this->assertCount(2, $todosFromDb);
        $this->assertEquals('incomplete', $savedRecords[0]->getStatus());
        $this->assertEquals('incomplete', $savedRecords[1]->getStatus());
    }

    /**
     * @test
     */
    public function getTodos_GivenExistingRecords_ReturnsOrderedTodos()
    {
        $records = [
            new Todo('name 1', 'desc 1', 'incomplete', new \DateTime('2018-09-09 10:00:00')),
            new Todo('name 2', 'desc 2', 'incomplete', new \DateTime('2018-09-07 10:00:00')),
            new Todo('name 3', 'desc 3', 'incomplete', new \DateTime('2018-09-08 10:00:00'))
        ];

        $savedRecords = [];
        foreach ($records as $record) {
            $savedRecords[] = $this->dao->saveTodo($record);
        }

        $todosFromDb = $this->dao->getTodos();

        $this->assertEquals('name 2', $todosFromDb[0]->getName());
        $this->assertEquals('name 3', $todosFromDb[1]->getName());
        $this->assertEquals('name 1', $todosFromDb[2]->getName());
    }


}