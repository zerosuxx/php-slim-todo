<?php

namespace TodoApp\Storage;

/**
 * Class Storage
 * @package TodoApp\Storage
 */
class ArrayStorage extends AbstractStorage
{
    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }
}