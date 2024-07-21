# PerformanceCache Package

The **PerformanceCache** package provides a robust caching solution that adheres to the PSR-16 (Simple Cache) interface, allowing developers to efficiently manage caching operations in PHP applications.

## Installation

You can install the PerformanceCache package via Composer. Run the following command in your terminal:

```bash
composer require codecorners/performance-cache
```

## Usage

### Initializing the Cache

To start using the cache, initialize an instance of `Cache`. By default, it uses `FileCacheHandler` for file-based caching:

```php
use CodeCorner\PerformanceCache\Cache;
use CodeCorner\PerformanceCache\FileCacheHandler;

// Initialize cache with default handler (FileCacheHandler)
$cache = new Cache();
```

You can optionally pass a custom cache handler to the constructor:

```php
use CodeCorner\PerformanceCache\Cache;
use App\CustomCacheHandler; // Replace with your custom cache handler

// Initialize cache with custom handler
$customHandler = new CustomCacheHandler();
$cache = new Cache($customHandler);
```

### Basic Cache Operations

#### Setting a Cache Value

```php
$key = 'my_key';
$value = 'my_value';
$ttl = 3600; // Optional TTL (time-to-live) in seconds

if ($cache->set($key, $value, $ttl)) {
    echo "Value successfully cached!\n";
} else {
    echo "Failed to cache the value.\n";
}
```

#### Getting a Cached Value

```php
$key = 'my_key';
$defaultValue = 'default_value'; // Optional default value if key not found

$cachedValue = $cache->get($key, $defaultValue);

echo "Cached Value: $cachedValue\n";
```

#### Deleting a Cached Value

```php
$key = 'my_key';

if ($cache->delete($key)) {
    echo "Cache entry successfully deleted!\n";
} else {
    echo "Failed to delete the cache entry.\n";
}
```

#### Clearing All Cached Values

```php
if ($cache->clear()) {
    echo "Cache cleared successfully!\n";
} else {
    echo "Failed to clear the cache.\n";
}
```

#### Working with Multiple Cache Entries

##### Getting Multiple Cache Entries

```php
$keys = ['key1', 'key2', 'key3'];
$defaultValue = 'default_value'; // Optional default value if any key is not found

$cachedValues = $cache->getMultiple($keys, $defaultValue);

foreach ($cachedValues as $key => $value) {
    echo "Key: $key, Value: $value\n";
}
```

##### Setting Multiple Cache Entries

```php
$values = [
    'key1' => 'value1',
    'key2' => 'value2',
    'key3' => 'value3',
];
$ttl = 3600; // Optional TTL for all entries

if ($cache->setMultiple($values, $ttl)) {
    echo "Multiple values successfully cached!\n";
} else {
    echo "Failed to cache multiple values.\n";
}
```

##### Deleting Multiple Cache Entries

```php
$keysToDelete = ['key1', 'key2', 'key3'];

if ($cache->deleteMultiple($keysToDelete)) {
    echo "Multiple cache entries deleted successfully!\n";
} else {
    echo "Failed to delete multiple cache entries.\n";
}
```

#### Checking if a Key Exists in Cache

```php
$key = 'my_key';

if ($cache->has($key)) {
    echo "Key '$key' exists in cache.\n";
} else {
    echo "Key '$key' does not exist in cache.\n";
}
```

### Error Handling

The `Cache` class provides basic error handling for cache operations. If an operation fails (e.g., cache read, write, delete), it logs the error message using `error_log()`.

## License

This package is licensed under the MIT License. See the [LICENSE](./LICENSE) file for details.

## Author

Written by Yash Gupta.

---

Replace placeholders such as `Yash Gupta` with your actual name or preferred pseudonym. Ensure the `LICENSE` file is present in your project directory and contains the appropriate license text for distribution.

This README file provides comprehensive guidance for developers looking to integrate the **PerformanceCache** package into their PHP projects, covering installation, basic usage examples, error handling considerations, and licensing information. Adjust the examples and instructions as per your specific implementation and documentation style.