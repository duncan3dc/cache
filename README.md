# cache
A simple [PSR-6](http://www.php-fig.org/psr/psr-6/)/[PSR-16](http://www.php-fig.org/psr/psr-16/) compatible disk cache for PHP
 
[![release](https://poser.pugx.org/duncan3dc/cache/version.svg)](https://packagist.org/packages/duncan3dc/cache)
[![build](https://github.com/duncan3dc/cache/workflows/.github/workflows/buildcheck.yml/badge.svg?branch=main)](https://github.com/duncan3dc/cache/actions?query=branch%3Amain+workflow%3A.github%2Fworkflows%2Fbuildcheck.yml)
[![coverage](https://codecov.io/gh/duncan3dc/cache/graph/badge.svg)](https://codecov.io/gh/duncan3dc/cache)


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
$cache = new \duncan3dc\Cache\FilesystemPool(sys_get_temp_dir());

# The $cache object implements PSR-6
$userData = $cache->getItem("user_data")->get();

# ...and PSR-16
$userData = $cache->get("user_data");
```

Using the `ArrayPool` will not persist data beyond the current request.

```php
$cache = new \duncan3dc\Cache\ArrayPool();

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


## duncan3dc/cache for enterprise

Available as part of the Tidelift Subscription

The maintainers of duncan3dc/cache and thousands of other packages are working with Tidelift to deliver commercial support and maintenance for the open source dependencies you use to build your applications. Save time, reduce risk, and improve code health, while paying the maintainers of the exact dependencies you use. [Learn more.](https://tidelift.com/subscription/pkg/packagist-duncan3dc-cache?utm_source=packagist-duncan3dc-cache&utm_medium=referral&utm_campaign=readme)
