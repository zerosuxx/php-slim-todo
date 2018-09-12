<?php

namespace TodoApp\Storage;

/**
 * Class Storage
 * @package TodoApp\Storage
 */
class Storage
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array &$data = [])
    {
        $this->setData($data);
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function &get(string $key, $default = null)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }
        return $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function remove(string $key)
    {
        if ($this->exists($key)) {
            unset($this->data[$key]);
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exists(string $key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @return array returns array data reference
     */
    public function &getArrayCopy()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    protected function setData(array &$data)
    {
        $this->data = &$data;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed|null
     */
    public function &consume(string $key, $default = null)
    {
        $value = &$this->get($key, $default);
        $this->remove($key);
        return $value;
    }
}