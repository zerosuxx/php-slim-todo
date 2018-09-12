<?php

namespace Test\TodoApp\Storage;

use PHPUnit\Framework\TestCase;
use TodoApp\Storage\Storage;

class StorageTest extends TestCase
{
    /**
     * @test
     */
    public function get_WithEmptyData_ReturnsDefaultValue()
    {
        $storage = new Storage();
        $this->assertNull($storage->get('not-exists'));
    }

    /**
     * @test
     */
    public function get_WithData_ReturnsValue()
    {
        $data = [
            'test-key' => 'test-value'
        ];
        $storage = new Storage($data);
        $this->assertEquals('test-value', $storage->get('test-key'));
    }

    /**
     * @test
     */
    public function get_WithData_ReturnsReferencedValue()
    {
        $data = [
            'test-key' => [
                'test-key2' => null
            ]
        ];
        $storage = new Storage($data);
        $storage->get('test-key')['test-key2'] = 'test-value2';
        $this->assertEquals('test-value2', $data['test-key']['test-key2']);
    }

    /**
     * @test
     */
    public function consume_WithData_ReturnsValueAndRemove()
    {
        $data = [
            'test-key' => 'test-value'
        ];
        $storage = new Storage($data);
        $value = $storage->consume('test-key');
        $this->assertEquals('test-value', $value);
        $this->assertFalse($storage->exists('test-key'));
    }

    /**
     * @test
     */
    public function set_WithEmptyData_ReturnsNewValue()
    {
        $storage = new Storage();
        $this->assertNull($storage->get('test-key'));
        $storage->set('test-key', 'test-value');
        $this->assertEquals('test-value', $storage->get('test-key'));
    }

    /**
     * @test
     */
    public function set_WithData_EditReference()
    {
        $data = [
            'test-key' => null
        ];
        $storage = new Storage($data);
        $storage->set('test-key', 'test-value');
        $this->assertEquals('test-value', $data['test-key']);
    }

    /**
     * @test
     */
    public function remove_WithData_ReturnsNull()
    {
        $data = [
            'test-key' => 'test-value'
        ];
        $storage = new Storage($data);
        $storage->remove('test-key');
        $this->assertNull($storage->get('test-key'));
    }

    /**
     * @test
     */
    public function has_WithNullData_ReturnsFalse()
    {
        $data = [
            'test-key' => null
        ];
        $storage = new Storage($data);
        $this->assertFalse($storage->has('test-key'));
    }

    /**
     * @test
     */
    public function has_WithData_ReturnsTrue()
    {
        $data = [
            'test-key' => 'test-value'
        ];
        $storage = new Storage($data);
        $this->assertTrue($storage->has('test-key'));
    }

    /**
     * @test
     */
    public function exists_WithNullData_ReturnsTrue()
    {
        $data = [
            'test-key' => null
        ];
        $storage = new Storage($data);
        $this->assertTrue($storage->exists('test-key'));
    }

    /**
     * @test
     */
    public function getArrayCopy_ReturnsReferencedData()
    {
        $data = [
            'test-key' => null
        ];
        $storage = new Storage($data);
        $this->assertSame($data, $storage->getArrayCopy());
        $storage->getArrayCopy()['test'] = 'test';
        $this->assertSame($data['test'], 'test');
    }

    /**
     * @test
     */
    public function toArray_ReturnsData()
    {
        $data = [
            'test-key' => null
        ];
        $storage = new Storage($data);
        $this->assertSame($data, $storage->toArray());
        $storage->toArray()['test'] = 'test';
        $this->assertArrayNotHasKey('test', $data);
    }

}