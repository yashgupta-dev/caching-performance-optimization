<?php

namespace CodeCorner\PerformanceCache;

use Psr\SimpleCache\CacheInterface;

class Cache implements CacheInterface
{
    protected $handler;
    protected $defaultTtl = 3600; // Default TTL is 1 hour

    public function __construct($handler = null)
    {
        $this->handler = $handler ?: new FileCacheHandler();
    }

    public function get($key, $default = null): mixed
    {
        try {
            return $this->handler->get($key, $default);
        } catch (\Exception $e) {
            // Handle cache read errors gracefully
            error_log("Cache read error: {$e->getMessage()}");
            return $default;
        }
    }

    public function set($key, $value, $ttl = null): bool
    {
        $ttl = $ttl ?? $this->defaultTtl;
        try {
            $this->handler->set($key, $value, $ttl);
            return true; // Successfully set
        } catch (\Exception $e) {
            // Handle cache write errors gracefully
            error_log("Cache write error: {$e->getMessage()}");
            return false; // Failed to set
        }
    }

    public function delete($key): bool
    {
        try {
            $this->handler->delete($key);
            return true; // Successfully deleted (or not present)
        } catch (\Exception $e) {
            // Handle cache delete errors gracefully
            error_log("Cache delete error: {$e->getMessage()}");
            return false; // Failed to delete
        }
    }

    public function clear(): bool
    {
        try {
            $this->handler->clear();
            return true; // Successfully cleared
        } catch (\Exception $e) {
            // Handle cache clear errors gracefully
            error_log("Cache clear error: {$e->getMessage()}");
        }
    }

    public function getMultiple($keys, $default = null): iterable
    {
        try {
            $values = [];
            foreach ($keys as $key) {
                $values[] = $this->handler->get($key, $default);
            }
            return $values;

        } catch (\Exception $e) {
            // Handle cache read errors gracefully
            error_log("Cache read error: {$e->getMessage()}");
            return [];
        }
    }

    public function setMultiple($values, $ttl = null): bool
    {
        $ttl = $ttl ?? $this->defaultTtl;
        try {

            foreach ($values as $key => $value) {
                $this->handler->set($key, $value, $ttl);
            }

            return true; // Successfully set
        } catch (\Exception $e) {
            // Handle cache write errors gracefully
            error_log("Cache write error: {$e->getMessage()}");
        }
    }

    public function deleteMultiple($keys): bool
    {
        try {
            foreach ($keys as $key) {
                $this->handler->delete($key);
            }    
            return true; // Successfully deleted (or not present)
        } catch (\Exception $e) {
            // Handle cache delete errors gracefully
            error_log("Cache delete error: {$e->getMessage()}");
        }
    }

    public function has($key): bool
    {
        try {
            return $this->handler->has($key);
        } catch (\Exception $e) {
            // Handle cache read errors gracefully
            error_log("Cache read error: {$e->getMessage()}");
            return false;
        }
    }
}
