<?php

use Test\SlimTestCase;
use TodoApp\Dao\TodosDao;
use TodoApp\Entity\Todo;

/**
 * Class TodosDaoTest
 */
class TodosDaoTest extends SlimTestCase
{
    /**
     * @test
     */
    public function getTodo_ReturnsOneTodoFromDb()
    {
        $dao = new TodosDao();
        $todo = $dao->getTodo(1);
        $this->assertInstanceOf(Todo::class, $todo);
    }
}