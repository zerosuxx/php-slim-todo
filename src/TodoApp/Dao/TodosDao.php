<?php

namespace TodoApp\Dao;

use TodoApp\Entity\Todo;

/**
 * Class TodosDao
 * @package TodoApp\Dao
 */
class TodosDao
{

    public function __construct()
    {
    }

    public function getTodo()
    {
        return new Todo();
    }
}