# Code Quality / Continuous Integration Tooling

The following packages are part of the MunkiReport PHP Code Quality Stack:

## Backend

* [larastan, a laravel wrapper for PHPStan](https://github.com/nunomaduro/larastan) performs static analysis of PHP types.
  * configuration is provided as phpstan.neon
  * your PHP should run with greater than the default 128M memory to use this.
* PHPUnit, for unit and feature tests
* PHP_CodeSniffer, for standardising code style

To run all quality tools:

```shell 
./vendor/bin/phpstan analyse --memory-limit=2G

```

## Frontend

* svelte-check for type checking typescript components and pruning dead css.

