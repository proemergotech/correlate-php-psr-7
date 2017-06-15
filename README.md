
## Overview

It's very difficult to track a request accross the system when we work microservices. We came out a solution for that. We generate a unique version 4 uuid for every request and every service passes this id via request header to other services. We call this **correlation ID**.

## Installation

- Install via composer

```sh
composer require proemergotech/correlate-php-psr-7
```

## Setup for Slim 3 framework

To use this class as a middleware, you can use ```->add( new ExampleMiddleware() );``` function chain after the ```$app```, ```Route```, or ```group()```, which in the code below, any one of these, could represent ```$subject```.

```php
$logger = $app['monolog']; // Must be \Monolog\Logger

$subject->add(new \ProEmergotech\Correlate\Psr7\Psr7CorrelateMiddleware($monolog));
```

Passing ```\Monolog\Logger``` is optional.

## Usage

This middleware automatically adds correlation id (coming from request header) to every log messages if you provided the optiona ```\Monolog\Logger``` instance to middleware's constructor.

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
