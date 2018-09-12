<?php

namespace Test\TodoApp\Storage;

use PHPUnit\Framework\TestCase;
use TodoApp\Storage\SessionStorage;

class SessionStorageTest extends TestCase
{

    protected function setUp()
    {
        $_SESSION = [];
    }

    /**
     * @test
     */
    public function get_WithData_ReturnsReferencedValue()
    {
        $_SESSION = [
            'test-key' => [
                'test-key2' => null
            ]
        ];
        $storage = new SessionStorage();
        $storage->get('test-key')['test-key2'] = 'test-value2';
        $this->assertEquals('test-value2', $_SESSION['test-key']['test-key2']);
    }

    /**
     * @test
     */
    public function set_WithData_EditReference()
    {
        $_SESSION = [
            'test-key' => null
        ];
        $storage = new SessionStorage();
        $storage->set('test-key', 'test-value');
        $this->assertEquals('test-value', $_SESSION['test-key']);
    }

    /**
     * @test
     */
    public function remove_WithData_RemoveDataFromReference()
    {
        $_SESSION = [
            'test-key' => null
        ];
        $storage = new SessionStorage();
        $this->assertNotEmpty($_SESSION);
        $storage->remove('test-key');
        $this->assertEmpty($_SESSION);
    }

    /**
     * @test
     */
    public function getData_ReturnsReferencedData()
    {
        $_SESSION = [
            'test-key' => null
        ];
        $storage = new SessionStorage();
        $this->assertSame($_SESSION, $storage->getData());
        $storage->getData()['test'] = 'test';
        $this->assertSame($_SESSION['test'], 'test');
    }

}