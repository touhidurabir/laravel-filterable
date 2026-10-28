# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]
- Laravel 11, 12 and 13 support added (`illuminate/support`, `illuminate/console` and `illuminate/http` constraints widened).
- PHPUnit 10, 11 and 12 support for tests; test methods now use `#[Test]` attributes alongside the `@test` annotations.
- Fixed PHP 8.4 implicit nullable parameter deprecations in `FilterBaseSetup::__construct`, `CommandExceptionHandler::outputConsoleException` and the `make:filter` command.
- Fixed PHP 8.1+ `explode()` null argument deprecation in `make:filter` when no `--filters` option is given.
- Fixed PHP 8.1+ `strlen()` null argument deprecation in `BaseQueryFilter::apply` when a filter value is `null` (e.g. an empty query param converted by `ConvertEmptyStringsToNull`).
- Code style formatted to PSR-12 with Laravel Pint (`composer format` / `composer lint`).


## [1.0.0] - 2021-09-16
- Initial release
