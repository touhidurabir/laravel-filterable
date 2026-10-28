# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased]
- Laravel 11, 12 and 13 support added (`illuminate/support`, `illuminate/console` and `illuminate/http` constraints widened).
- PHPUnit 10, 11 and 12 support for tests; test methods now use `#[Test]` attributes alongside the `@test` annotations.
- Fixed PHP 8.4 implicit nullable parameter deprecations in `FilterBaseSetup::__construct`, `CommandExceptionHandler::outputConsoleException` and the `make:filter` command.
- Fixed PHP 8.1+ `explode()` null argument deprecation in `make:filter` when no `--filters` option is given.
- Fixed PHP 8.1+ `strlen()` null argument deprecation in `BaseQueryFilter::apply` when a filter value is `null` (e.g. an empty query param converted by `ConvertEmptyStringsToNull`).
- Code style formatted to PSR-12 with Laravel Pint (`composer format` / `composer lint`).
- Replaced Travis CI with GitHub Actions (PHP 8.2–8.4 × Laravel 11/12/13, plus a code style check).
- Fixed `ArgumentCountError` for an empty filter param (e.g. `?name=`): `make:filter` now generates query filter methods as `{method}($value = null)`. Existing generated filter classes are not changed; add the `= null` default to their methods by hand.
- Fixed the README examples to use the `--filters=` option (they showed `--filter=`, which `make:filter` rejects).


## [1.0.0] - 2021-09-16
- Initial release
