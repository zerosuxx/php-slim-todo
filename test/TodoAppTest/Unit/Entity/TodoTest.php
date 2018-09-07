<?php

namespace Test\TodoAppTest\Unit\Entity;

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
        $status = 'complete';
        $dueAt = new DateTime();
        $id = 1;

        $todo = new Todo($name, $description, $status, $dueAt, $id);

        $this->assertEquals($name, $todo->getName());
        $this->assertEquals($description, $todo->getDescription());
        $this->assertEquals($status, $todo->getStatus());
        $this->assertEquals($dueAt, $todo->getDueAt());
        $this->assertEquals($id, $todo->getId());
    }

    /**
     * @test
     */
    public function getId_IdIsNotGiven_ReturnsNull()
    {
        $name = 'Test name';
        $description = 'Description';
        $status = 'complete';
        $dueAt = new DateTime();

        $todo = new Todo($name, $description, $status, $dueAt);

        $this->assertNull($todo->getId());
    }



}