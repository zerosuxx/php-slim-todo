<?php

namespace TodoApp\Storage;

/**
 * Class AbstractStorage
 * @package TodoApp\Storage
 */
abstract class AbstractStorage implements StorageInterface
{
    use StorageTrait;
}