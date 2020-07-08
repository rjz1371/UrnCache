<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About UrnCache

Laravel UrnCache provides a cache system with grouping ability.

**Quick Start**

// install
<pre>
composer require rjz1371/urncache
composer update
</pre>

// namespace
use rjz1371\UrnCache\UrnCache;

// Put cache data without expire time ( forever cache ).
$key = 'my-cache-key';
$value = [
    ['username' => 'rjz1371', 'age' => 25],
    ['username' => 'reza', 'age' => 20],
    ['username' => 'alex', 'age' => 56]
];
$group = 'users';
UrnCache::put($key, $value, $group);

// Put cache data with expire time ( expire after 1 hour ).
$key = 'my-cache-key';
$value = [
    ['username' => 'rjz1371', 'age' => 25],
    ['username' => 'reza', 'age' => 20],
    ['username' => 'alex', 'age' => 56]
];
$group = 'users';
UrnCache::put($key, $value, $group, 3600);

// Checking cache exists or not ( $result is true or false ).
$key = 'my-cache-key';
$group = 'users';
$result = UrnCache::has($key, $group);

// Retrive cache data.
$key = 'my-cache-key';
$group = 'users';
$result = UrnCache::get($key, $group);

// Delete cache.
$key = 'my-cache-key';
$group = 'users';
UrnCache::delete($key, $group);

// Delete all cache in special group.
$group = 'users';
UrnCache::deleteByGroup($group);

// Delete all cache.
UrnCache::deleteAll();

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
