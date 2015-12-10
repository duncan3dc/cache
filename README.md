# cache
A simple [PSR-6](http://www.php-fig.org/psr/psr-6/) compatible disk cache for PHP
 
[![Build Status](https://img.shields.io/travis/duncan3dc/cache.svg)](https://travis-ci.org/duncan3dc/cache)
[![Latest Version](https://img.shields.io/packagist/v/duncan3dc/cache.svg)](https://packagist.org/packages/duncan3dc/cache)


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
```

Using the `ArrayPool` will not persist data beyond the current request.

```php
$cache = new \duncan3dc\Cache\ArrayPool;

# The $cache object implements PSR-6
$userData = $cache->getItem("user_data")->get();
```


## Changelog
A [Changelog](CHANGELOG.md) has been available since the beginning of time


## Where to get help
Found a bug? Got a question? Just not sure how something works?  
Please [create an issue](//github.com/duncan3dc/cache/issues) and I'll do my best to help out.  
Alternatively you can catch me on [Twitter](https://twitter.com/duncan3dc)
