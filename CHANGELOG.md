# Changelog

All notable changes to `eloquent-hashids` will be documented in this file.

## [v1.0.1] - 2025-12-10

### Added
- `resolveHashidRouteBinding()` helper method - Allows compatibility with models that already have a custom `resolveRouteBinding()` method
- `@property-read` PHPDoc annotation for better IDE support

### Changed
- Updated README documentation with custom route binding examples

## [v1.0.0] - 2025-12-10

### Added
- Initial release
- `Hashidable` trait with automatic hashid generation
- Custom `HashidEncoder` (zero dependencies)
- `HashidableConfigInterface` for per-model configuration
- Automatic route model binding support
- `findByHashid()` and `findByHashidOrFail()` static methods
- Configurable prefix, suffix, length, and salt
- Full test suite (13 tests)
- Support for Laravel 10, 11, 12 and PHP 8.2+
