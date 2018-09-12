<?php

namespace TodoApp\Storage;

/**
 * Class Storage
 * @package TodoApp\Storage
 */
class ArrayStorage extends \ArrayObject implements StorageInterface
{
    use StorageTrait;
    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data, self::ARRAY_AS_PROPS);
        $this->setData($data);
    }
}