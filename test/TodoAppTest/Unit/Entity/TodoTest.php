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
    public function getName_TodoExistsWithName_ReturnsName()
    {
        $name = 'Test name';
        $todo = new Todo($name, '', '', new DateTime());

        $this->assertEquals($name, $todo->getName());
    }

    /**
     * @test
     */
    public function getDescription_TodoExistsWithDescription_ReturnsDescription()
    {
        $description = 'Description';
        $todo = new Todo('', $description, '', new DateTime());

        $this->assertEquals($description, $todo->getDescription());
    }

    /**
     * @test
     */
    public function getStatus_TodoExistsWithStatus_ReturnsStatus()
    {
        $status = 'complete';
        $todo = new Todo('', '', $status, new DateTime());

        $this->assertEquals($status, $todo->getStatus());
    }

    /**
     * @test
     */
    public function getDueAt_TodoExistsWithDueDate_ReturnsDueAt()
    {
        $dueAt = new DateTime();
        $todo = new Todo('', '', '', $dueAt);

        $this->assertEquals($dueAt, $todo->getDueAt());
    }

}