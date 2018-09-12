<?php

namespace TodoApp\Storage;

/**
 * Class SessionStorage
 * @package TodoApp\Storage
 */
class SessionStorage extends AbstractStorage
{
    public function __construct()
    {
        $this->setData($_SESSION);
    }
}