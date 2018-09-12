<?php

namespace TodoApp\Storage;

/**
 * Class SessionStorage
 * @package TodoApp\Storage
 */
class SessionStorage implements StorageInterface
{
    use StorageTrait;

    public function __construct()
    {
        $this->setData($_SESSION);
    }
}