<?php
namespace TodoApp\Storage;


trait StorageTrait
{
    /**
     * @var array
     */
    private $data;

    /**
     * {@inheritDoc}
     */
    public function &get(string $key, $default = null)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }
        return $default;
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function remove(string $key)
    {
        if ($this->exists($key)) {
            unset($this->data[$key]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function &consume(string $key, $default = null)
    {
        $value = &$this->get($key, $default);
        $this->remove($key);
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $key)
    {
        return isset($this->data[$key]);
    }

    /**
     * {@inheritDoc}
     */
    public function exists(string $key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function &getSourceData()
    {
        return $this->data;
    }

    /**
     * {@inheritDoc}
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
}