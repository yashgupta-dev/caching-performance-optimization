<?php

use CodeCorner\PerformanceCache\Cache;
use PHPUnit\Framework\TestCase;

class CacheTest extends TestCase
{
    protected $cache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cache = new Cache();
    }

    public function testSetAndGet()
    {
        // Test basic set and get functionality
        $this->cache->set('test_key', 'test_value', 60);
        $cachedValue = $this->cache->get('test_key');
        $this->assertEquals('test_value', $cachedValue);

        // Test setting with different TTL and retrieval
        $this->cache->set('test_key_ttl', 'value_ttl', 120);
        $cachedValueTTL = $this->cache->get('test_key_ttl');
        $this->assertEquals('value_ttl', $cachedValueTTL);

        // Test setting and retrieving an array
        $arrayValue = ['key' => 'value'];
        $this->cache->set('test_key_array', $arrayValue);
        $cachedArrayValue = $this->cache->get('test_key_array');
        $this->assertEquals($arrayValue, $cachedArrayValue);

        // Test setting and retrieving a large string
        $largeStringValue = str_repeat('a', 1000000); // 1 MB string
        $this->cache->set('test_key_large_string', $largeStringValue);
        $cachedLargeStringValue = $this->cache->get('test_key_large_string');
        $this->assertEquals($largeStringValue, $cachedLargeStringValue);
    }

    public function testDelete()
    {
        // Test deleting an existing key
        $this->cache->set('test_key_delete', 'value');
        $this->cache->delete('test_key_delete');
        $cachedValue = $this->cache->get('test_key_delete');
        $this->assertNull($cachedValue);

        // Test deleting a non-existing key
        $result = $this->cache->delete('non_existing_key');
        $this->assertTrue($result); // Deleting a non-existing key should return true
    }

    public function testClear()
    {
        // Test clearing the cache
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');
        $this->cache->clear();
        $cachedValue1 = $this->cache->get('key1');
        $cachedValue2 = $this->cache->get('key2');
        $this->assertNull($cachedValue1);
        $this->assertNull($cachedValue2);
    }

    public function testGetMultiple()
    {
        // Test getting multiple existing keys
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');
        $cachedValues = $this->cache->getMultiple(['key1', 'key2']);
        $this->assertEquals(['key1' => 'value1', 'key2' => 'value2'], $cachedValues);

        // Test getting multiple keys with one non-existing key
        $this->cache->set('key3', 'value3');
        $cachedValues = $this->cache->getMultiple(['key1', 'key3', 'non_existing_key']);
        $this->assertEquals(['key1' => null, 'key3' => 'value3', 'non_existing_key' => null], $cachedValues);
    }

    public function testSetMultiple()
    {
        // Test setting multiple values
        $values = ['key1' => 'value1', 'key2' => 'value2'];
        $this->cache->setMultiple($values);
        $cachedValue1 = $this->cache->get('key1');
        $cachedValue2 = $this->cache->get('key2');
        $this->assertEquals('value1', $cachedValue1);
        $this->assertEquals('value2', $cachedValue2);

        // Test setting multiple values with TTL
        $valuesTTL = ['key3' => 'value3', 'key4' => 'value4'];
        $this->cache->setMultiple($valuesTTL, 120);
        $cachedValue3 = $this->cache->get('key3');
        $cachedValue4 = $this->cache->get('key4');
        $this->assertEquals('value3', $cachedValue3);
        $this->assertEquals('value4', $cachedValue4);
    }

    public function testDeleteMultiple()
    {
        // Test deleting multiple existing keys
        $this->cache->set('key1', 'value1');
        $this->cache->set('key2', 'value2');
        $this->cache->deleteMultiple(['key1', 'key2']);
        $cachedValue1 = $this->cache->get('key1');
        $cachedValue2 = $this->cache->get('key2');
        $this->assertNull($cachedValue1);
        $this->assertNull($cachedValue2);

        // Test deleting multiple keys with one non-existing key
        $this->cache->set('key3', 'value3');
        $this->cache->deleteMultiple(['key1', 'key3', 'non_existing_key']);
        $cachedValue3 = $this->cache->get('key3');
        $this->assertEquals('value3', $cachedValue3);
    }

    public function testHas()
    {
        // Test checking existence of an existing key
        $this->cache->set('key1', 'value1');
        $result1 = $this->cache->has('key1');
        $this->assertTrue($result1);

        // Test checking existence of a non-existing key
        $result2 = $this->cache->has('non_existing_key');
        $this->assertFalse($result2);
    }
}
