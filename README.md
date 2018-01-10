# cache
A simple [PSR-6](http://www.php-fig.org/psr/psr-6/)/[PSR-16](http://www.php-fig.org/psr/psr-16/) compatible disk cache for PHP
 
[![Latest Stable Version](https://poser.pugx.org/duncan3dc/cache/version.svg)](https://packagist.org/packages/duncan3dc/cache)
[![Build Status](https://travis-ci.org/duncan3dc/cache.svg?branch=master)](https://travis-ci.org/duncan3dc/cache)
[![Coverage Status](https://coveralls.io/repos/github/duncan3dc/cache/badge.svg)](https://coveralls.io/github/duncan3dc/cache)


## Installation

The recommended method of installing this library is via [Composer](//getcomposer.org/).

Run the following command from your project root:

```bash
$ composer require duncan3dc/cache
```


## Quick Examples

There are 2 cache providers available, one is the local filesystem and the other is simple array cache in memory.

Using the `FilesystemPool` will persist data forever (or until the filesystem is purged).

```php
$cache = new \duncan3dc\Cache\FilesystemPool;

# The $cache object implements PSR-6
$userData = $cache->getItem("user_data")->get();

# ...and PSR-16
$userData = $cache->get("user_data");
```

Using the `ArrayPool` will not persist data beyond the current request.

```php
$cache = new \duncan3dc\Cache\ArrayPool;

# The $cache object implements PSR-6
$userData = $cache->getItem("user_data")->get();

# ...and PSR-16
$userData = $cache->get("user_data");
```

There's also a trait to allow any method to be automatically cached.

```php
$cache = new class {
    use \duncan3dc\Cache\CacheCallsTrait;

    public function _getData()
    {
        return [];
    }
};

$cache->getData();
```

The first time `getData()` is called then `_getData()` will be run, but after that future calls to `getData()` will just return the cached result from the first call to `_getData()`.


## Changelog
A [Changelog](CHANGELOG.md) has been available since the beginning of time


## Where to get help
Found a bug? Got a question? Just not sure how something works?  
Please [create an issue](//github.com/duncan3dc/cache/issues) and I'll do my best to help out.  
Alternatively you can catch me on [Twitter](https://twitter.com/duncan3dc)
