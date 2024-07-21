<?php

namespace CodeCorner\PerformanceCache;

/**
 * FileCacheHandler
 */
class FileCacheHandler
{    
    /**
     * cacheDir
     *
     * @var mixed
     */
    protected $cacheDir;
    
    /**
     * __construct
     *
     * @param  mixed $cacheDir
     * @return void
     */
    public function __construct($cacheDir = null)
    {
        $this->cacheDir = $cacheDir ?: sys_get_temp_dir() . '/performance-cache';
        if (!file_exists($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }
    
    /**
     * get
     *
     * @param  mixed $key
     * @param  mixed $default
     * @return array|string|null|json
     */
    public function get($key, $default = null)
    {
        $filename = $this->getCacheFilename($key);
        if (!file_exists($filename)) {
            return $default;
        }

        $data = unserialize(file_get_contents($filename));
        if (time() > $data['expire']) {
            unlink($filename);
            return $default;
        }

        return $data['value'];
    }
    
    /**
     * set
     *
     * @param  mixed $key
     * @param  mixed $value
     * @param  mixed $ttl
     * @return void
     */
    public function set($key, $value, $ttl)
    {
        $filename = $this->getCacheFilename($key);
        $data = serialize([
            'value' => $value,
            'expire' => time() + $ttl,
        ]);
        file_put_contents($filename, $data);
    }
    
    /**
     * delete
     *
     * @param  mixed $key
     * @return void
     */
    public function delete($key)
    {
        $filename = $this->getCacheFilename($key);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
    
    /**
     * clear
     *
     * @return void
     */
    public function clear()
    {
        $files = glob($this->cacheDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
    
    /**
     * getCacheFilename
     *
     * @param  mixed $key
     * @return string|null
     */
    protected function getCacheFilename($key)
    {
        return $this->cacheDir . '/' . md5($key);
    }
}
