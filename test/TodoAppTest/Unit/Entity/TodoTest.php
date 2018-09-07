<?php

namespace Test\TodoAppTest\Unit\Entity;

use PHPUnit\Framework\TestCase;
use TodoApp\Entity\Todo;

class TodoTest extends TestCase
{
    /**
     * @test
     */
    public function getName_GivenOneParameter_ReturnsName()
    {
        $name = 'Test name';
        $todo = new Todo($name);
        $this->assertEquals($name, $todo->getName());
    }
}