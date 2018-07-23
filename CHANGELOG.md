Changelog
=========

## x.y.z - UNRELEASED

--------

## 0.6.1 - 2018-07-23

### Fixed

* [FilesystemPool] Attempt to create the parent directories of the cache.

--------

## 0.6.0 - 2018-07-21

### Fixed

* [General] TTL is now supported everywhere.

### Changed

* [Support] Drop support for PHP 7.0

--------

## 0.5.0 - 2018-05-30

### Fixed

* [Dependencies] Allow any version of the PSRs to be installed.

### Changed

* [CacheCallTrait] Add some type hints.
* [Support] Drop support for PHP 5.6

--------

## 0.4.0 - 2018-03-27

### Fixed

* [CacheCallTrait] Ensure methods actually exist before trying to call them.

--------

## 0.3.0 - 2018-01-10

### Changed

* All cache keys are now validated as per the PSR rules.

--------

## 0.2.0 - 2017-10-12

### Added

* [CacheCallsTrait] Add a cacheMethod() to manually call the cached version.

--------

## 0.1.0 - 2017-03-13

### Added

* [FilesystemPool] Created a filesystem pool for long lived cache.
* [ArrayPool] Created an array pool for short lived cache.
* [PSR-6] All pools are compatible with PSR-6
* [PSR-16] All pools are compatible with PSR-16
* [CacheCallsTrait] Allow method calls to be easily cached.

--------
