<?php

namespace Test\TodoApp\Integration\Dao;

use Test\TodoApp\TodoAppTestCase;
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
        $this->getPDO()->query(
            "INSERT INTO todos (name, description, status, due_at) VALUES ('Test name', 'test desc', 'incomplete', '2018-09-07 10:00:00')"
        );
        $todo = $this->todosDao->getTodo(1);
        $this->assertInstanceOf(Todo::class, $todo);
        $this->assertEquals(1, $todo->getId());
        $this->assertEquals('Test name', $todo->getName());
        $this->assertEquals('test desc', $todo->getDescription());
        $this->assertEquals('incomplete', $todo->getStatus());
        $this->assertEquals('2018-09-07 10:00:00', $todo->getDueAtTimestamp());
    }

    /**
     * @test
     */
    public function getTodo_GivenEmptyDatabase_ThrowsException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->todosDao->getTodo(999);
    }

    /**
     * @test
     */
    public function saveTodo_EmptyDatabase_ReturnsInsertedTodo()
    {
        $todo = $this->buildTodo('name', 'desc', new \DateTime('2018-09-07 10:00:00'));

        $savedTodo = $this->todosDao->saveTodo($todo);

        $todoFromDb = $this->todosDao->getTodo(1);

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
            $this->buildTodo('name 1', 'desc 1', new \DateTime('2018-09-07 10:00:00')),
            $this->buildTodo('name 2', 'desc 2', new \DateTime('2018-09-07 10:00:00')),
            $this->buildTodo('name 3', 'desc 3', new \DateTime('2018-09-07 10:00:00'))
        ];

        $savedRecords = [];
        foreach ($records as $record) {
            $savedRecords[] = $this->todosDao->saveTodo($record);
        }

        $todosFromDb = $this->todosDao->getTodos();

        $this->assertEquals($todosFromDb, $savedRecords);
    }

    /**
     * @test
     */
    public function getTodos_GivenExistingRecords_ReturnsInCompletedTodos()
    {
        $records = [
            $this->buildTodo('name 1', 'desc 1', new \DateTime('2018-09-07 10:00:00')),
            $this->buildTodo('name 2', 'desc 2', new \DateTime('2018-09-07 10:00:00')),
            $this->buildTodo('name 3', 'desc 3', new \DateTime('2018-09-07 10:00:00'), Todo::STATUS_COMPLETE)
        ];

        $savedRecords = [];
        foreach ($records as $record) {
            $savedRecords[] = $this->todosDao->saveTodo($record);
        }

        $todosFromDb = $this->todosDao->getTodos();

        $this->assertCount(2, $todosFromDb);
        $this->assertEquals('incomplete', $savedRecords[0]->getStatus());
        $this->assertEquals('incomplete', $savedRecords[1]->getStatus());
    }

    /**
     * @test
     */
    public function updateTodo_GivenExistingTodo_UpdatesTodo()
    {
        $todoToSave = $this->buildTodo('name 1', 'desc 1', new \DateTime('2018-09-09 10:00:00'));

        $this->todosDao->saveTodo($todoToSave);

        $todoToModify = $this->buildTodo('name 2', 'desc 2', new \DateTime('2019-09-09 10:00:00'), Todo::STATUS_COMPLETE, 1);

        $updated = $this->todosDao->updateTodo($todoToModify);

        $todoFromDb = $this->todosDao->getTodo(1);

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
        $newTodo = $this->buildTodo('name 1', 'desc 1', new \DateTime('2018-09-09 10:00:00'));

        $savedTodo = $this->todosDao->saveTodo($newTodo);

        $updatedTodo = $this->todosDao->completeTodo($savedTodo);

        $todoFromDb = $this->todosDao->getTodo(1);

        $this->assertEquals('complete', $updatedTodo->getStatus());
        $this->assertEquals('complete', $todoFromDb->getStatus());
    }

    /**
     * @test
     */
    public function deleteTodo_GivenExistingTodo_DeletesTodo()
    {
        $newTodo = $this->buildTodo('name 1', 'desc 1', new \DateTime('2018-09-09 10:00:00'));

        $savedTodo = $this->todosDao->saveTodo($newTodo);

        $this->assertCount(1, $this->todosDao->getTodos());

        $deleted = $this->todosDao->deleteTodo($savedTodo);

        $this->assertCount(0, $this->todosDao->getTodos());
        $this->assertTrue($deleted);
    }

    /**
     * @test
     */
    public function createTodoFromArray_ReturnsNewTodo()
    {
        $todoData = [
            'name' => 'Test Name',
            'description' => 'Test desc',
            'status' => 'incomplete',
            'due_at' => '2018-09-10 11:26:00'
        ];
        $todo = $this->todosDao->createTodoFromArray($todoData);

        $this->assertEquals($todoData['name'], $todo->getName());
        $this->assertEquals($todoData['description'], $todo->getDescription());
        $this->assertEquals($todoData['status'], $todo->getStatus());
        $this->assertEquals($todoData['due_at'], $todo->getDueAtTimestamp());
        $this->assertNull($todo->getId());
    }

}