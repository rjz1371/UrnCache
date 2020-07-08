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

<b>installation</b>
<pre>
composer require rjz1371/urncache
composer update
</pre>

<b>namespace</b>
<pre>
use rjz1371\UrnCache\UrnCache;
</pre>

<b>Put cache data without expire time ( forever cache ).</b>
<pre>
$key = 'my-cache-key';
$value = [
    ['username' => 'rjz1371', 'age' => 25],
    ['username' => 'reza', 'age' => 20],
    ['username' => 'alex', 'age' => 56]
];
$group = 'users';
UrnCache::put($key, $value, $group);
</pre>

<b>Put cache data with expire time ( expire after 1 hour ).</b>
<pre>
$key = 'my-cache-key';
$value = [
    ['username' => 'rjz1371', 'age' => 25],
    ['username' => 'reza', 'age' => 20],
    ['username' => 'alex', 'age' => 56]
];
$group = 'users';
UrnCache::put($key, $value, $group, 3600);
</pre>

<b>Checking cache exists or not ( $result is true or false ).</b>
<pre>
$key = 'my-cache-key';
$group = 'users';
$result = UrnCache::has($key, $group);
</pre>

<b>Retrive cache data.</b>
<pre>
$key = 'my-cache-key';
$group = 'users';
$result = UrnCache::get($key, $group);
</pre>

<b>Delete cache.</b>
<pre>
$key = 'my-cache-key';
$group = 'users';
UrnCache::delete($key, $group);
</pre>

<b>Delete all cache in special group.</b>
<pre>
$group = 'users';
UrnCache::deleteByGroup($group);
</pre>

<b>Delete all cache.</b>
<pre>
UrnCache::deleteAll();
</pre>

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
