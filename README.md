# correlate-php-psr-7

---

## Overview

It's very difficult to track a request accross the system when we are working with microservices. We came out a solution for that. We generate a unique version 4 uuid for every request and every service passes this id via request header to other services. We call this **correlation ID**.

## Packages

- [proemergotech/correlate-php-laravel](https://github.com/proemergotech/correlate-php-laravel)
  - Middleware for Laravel and Lumen frameworks.
- [proemergotech/correlate-php-psr-7](https://github.com/proemergotech/correlate-php-psr-7)
  - Middleware for any PSR-7 compatible frameworks like [Slim Framework](https://www.slimframework.com/).
- [proemergotech/correlate-php-monolog](https://github.com/proemergotech/correlate-php-monolog)
  - Monolog processor for correlate middlewares (you don't have to use this directly).
- [proemergotech/correlate-php-guzzle](https://github.com/proemergotech/correlate-php-guzzle)
  - Guzzle middleware to add correlation id to every requests.
- [proemergotech/correlate-php-core](https://github.com/proemergotech/correlate-php-core)
  - Common package for correlate id middlewares to provide consistent header naming accross projects.

## Installation

- Install via composer

```sh
composer require proemergotech/correlate-php-psr-7
```

## Setup for Slim 3 framework

To use this class as a middleware, you can use ```->add( new ExampleMiddleware() );``` function chain after the ```$app```, ```Route```, or ```group()```, which in the code below, any one of these, could represent ```$subject```.

```php
$logger = $app['monolog']; // Must be \Monolog\Logger

$subject->add(new \ProEmergotech\Correlate\Psr7\Psr7CorrelateMiddleware($logger));
```

Passing ```\Monolog\Logger``` is optional.

## Usage

This middleware automatically adds correlation id (coming from request header) to every log messages if you provided the optional ```\Monolog\Logger``` instance to middleware's constructor.

You can access the correlation id if you want to work with it.

```php
$cid = $request->getAttribute(\ProEmergotech\Correlate\Correlate::getParamName());
```

## Contributing

See `CONTRIBUTING.md` file.

## Credits

This package developed by [Soma Szélpál](https://github.com/shakahl/) at [Pro Emergotech Ltd.](https://github.com/proemergotech/).

## License

This project is released under the [MIT License](http://www.opensource.org/licenses/MIT).
