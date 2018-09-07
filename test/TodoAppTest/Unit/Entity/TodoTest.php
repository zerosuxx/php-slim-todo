<?php

namespace Test\TodoAppTest\Unit\Entity;

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
        $todo = new Todo($name, '');

        $this->assertEquals($name, $todo->getName());
    }

    /**
     * @test
     */
    public function getDescription_TodoExistsWithDescription_ReturnsDescription()
    {
        $description = 'Description';
        $todo = new Todo('', $description);

        $this->assertEquals($description, $todo->getDescription());
    }

    /**
     * @test
     */
    public function getStatus_TodoExistsWithStatus_ReturnsStatus()
    {
        $status = 'complete';
        $todo = new Todo('', '', $status);

        $this->assertEquals($status, $todo->getStatus());
    }
}