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
    public function updateTodo_GivenExistingTodo_UpdatesTodo()
    {
        $todoToSave = new Todo('name 1', 'desc 1', 'incomplete', new \DateTime('2018-09-09 10:00:00'));

        $this->dao->saveTodo($todoToSave);

        $todoToModify = new Todo('name 2', 'desc 2', 'complete', new \DateTime('2019-09-09 10:00:00'), 1);

        $updated = $this->dao->updateTodo($todoToModify);

        $todoFromDb = $this->dao->getTodo(1);

        $this->assertTrue($updated);
        $this->assertEquals('name 2', $todoFromDb->getName());
        $this->assertEquals('desc 2', $todoFromDb->getDescription());
        $this->assertEquals('complete', $todoFromDb->getStatus());
        $this->assertEquals(new \DateTime('2019-09-09 10:00:00'), $todoFromDb->getDueAt());
    }

    /**
     * @test
     */
    public function updateTodoStatus_GivenExistingTodo_UpdatesTodoStatus()
    {
        $newTodo = new Todo('name 1', 'desc 1', 'incomplete', new \DateTime('2018-09-09 10:00:00'));

        $savedTodo = $this->dao->saveTodo($newTodo);

        $updatedTodo = $this->dao->completeTodo($savedTodo);

        $todoFromDb = $this->dao->getTodo(1);

        $this->assertEquals('complete', $updatedTodo->getStatus());
        $this->assertEquals('complete', $todoFromDb->getStatus());
    }

    /**
     * @test
     */
    public function deleteTodo_GivenExistingTodo_DeletesTodo()
    {
        $newTodo = new Todo('name 1', 'desc 1', 'incomplete', new \DateTime('2018-09-09 10:00:00'));

        $savedTodo = $this->dao->saveTodo($newTodo);

        $this->assertCount(1, $this->dao->getTodos());

        $deleted = $this->dao->deleteTodo($savedTodo);

        $this->assertCount(0, $this->dao->getTodos());
        $this->assertTrue($deleted);
    }

}