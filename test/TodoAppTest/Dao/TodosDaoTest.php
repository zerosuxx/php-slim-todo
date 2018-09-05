<?php

namespace Test\TodoAppTest;

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
        $dao = new TodosDao();
        $todo = $dao->getTodo(1);
        $this->assertInstanceOf(Todo::class, $todo);
    }
}