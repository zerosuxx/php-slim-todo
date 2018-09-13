<?php

namespace Test\TodoApp\Unit\Entity;

use DateTime;
use PHPUnit\Framework\TestCase;
use TodoApp\Entity\Todo;

class TodoTest extends TestCase
{
    /**
     * @test
     */
    public function getters_GivenParameters_ReturnsPropertyValues()
    {
        $name = 'Test name';
        $description = 'Description';
        $dueAt = new DateTime();
        $status = Todo::STATUS_INCOMPLETE;
        $id = 1;

        $todo = new Todo($name, $description, $dueAt, $status, $id);

        $this->assertEquals($name, $todo->getName());
        $this->assertEquals($description, $todo->getDescription());
        $this->assertEquals($dueAt, $todo->getDueAt());
        $this->assertEquals($status, $todo->getStatus());
        $this->assertEquals($id, $todo->getId());
    }

    /**
     * @test
     */
    public function getId_IdIsNotGiven_ReturnsNull()
    {
        $name = 'Test name';
        $description = 'Description';
        $dueAt = new DateTime();

        $todo = new Todo($name, $description, $dueAt);

        $this->assertNull($todo->getId());
    }

    /**
     * @test
     */
    public function getDueAtTimestamp_ReturnsDueAtInTimestamp()
    {
        $name = 'Test name';
        $description = 'Description';
        $dueAt = new DateTime();

        $todo = new Todo($name, $description, $dueAt);

        $this->assertEquals($dueAt->format('Y-m-d H:i:s'), $todo->getDueAtTimestamp());
    }

    /**
     * @test
     */
    public function withId_ReturnsNewInstanceWithDifferentId()
    {
        $name = 'Test name';
        $description = 'Description';
        $dueAt = new DateTime();
        $status = Todo::STATUS_INCOMPLETE;

        $todo = new Todo($name, $description, $dueAt, $status, 1);

        $newTodo = $todo->withId(2);
        $this->assertEquals(1, $todo->getId());
        $this->assertEquals(2, $newTodo->getId());
    }


    /**
     * @test
     */
    public function withStatus_ReturnsNewInstanceWithDifferentStatus()
    {
        $name = 'Test name';
        $description = 'Description';
        $dueAt = new DateTime();

        $todo = new Todo($name, $description, $dueAt);

        $newTodo = $todo->withStatus('complete');
        $this->assertEquals(Todo::STATUS_INCOMPLETE, $todo->getStatus());
        $this->assertEquals(Todo::STATUS_COMPLETE, $newTodo->getStatus());
    }

}