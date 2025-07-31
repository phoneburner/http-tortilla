# phoneburner/link-tortilla Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/)
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 2.0.0 [2025-07-30]

Major changes to the previous unreleased version to bring everything up to date
with the latest versions of PHP, PSR-7, and PHPUnit.

### Added

- Add "Dockerfile", "docker-compose.yml", and ".dockerignore" files to create a standardized isolated development
  environment for contributors.
- Add support for PHP 8.2, 8.3, and 8.4
- Add missing code coverage for `StreamWrapper::tell()`
- A Makefile was added for easier management of common tasks such as cleaning build artifacts, starting a bash shell in
  the development container, running tests, rectifying code style issues, and running PHPStan code analysis.
- Standardized editor configuration was added via ".editorconfig" which automatically sets up indentation, character
  set, and trimming options for the supported types of files.
- Add and configure PHPStan as a project development dependency, updating code to pass at max level
- Add and configure Rector as a project development dependency, running the applicable standard rule sets
- Add SECURITY.md file
- Add CHANGELOG.md file
- Add CONTRIBUTORS.md file
- Add other standard project skeleton files, including ".gitattributes" and ".gitignore"'

### Changed

- Dependency on `psr/http-message` package updated to "^1.0 || ^2.0"
- Return and parameter types have been defined where possible, and in compliance with the PSR-7 "static" return
  requirements. This means that the callback passed to the `setFactory()` methods MUST return `static`
- Tests have been overhauled to work with PHPUnit 10.5, using attributes, and have been cleaned up to pass PHPStan on
  max settings
- Updated the PHP_CodeSniffer configuration to align with our current organization standards
- Updated the copyright year of the project `License` file
- Update README.md to reflect these changes

### Deprecated

- Nothing.

### Removed

- Dropped support for PHP versions less than 8.1.

### Fixed

- Completed code coverage for StreamWrapper::tell()

## 1.0.0-rc1 [2020-12-17]

### Added

- Strict Type Declarations
- Type Declarations Where Possible

### Changed

- Updated minimum PHP version to 7.4
- Expand CI testing scope to PHP 8.0

## 1.0.0-beta2 [2020-08-18]

### Added

- Add Support for wrapping `UploadedFileInterface`

### Changed

- Expand CI testing scope to PHP 7.4

### Fixed

- Fixes the overly aggressive type declarations

## 1.0.0-beta1 [2020-08-18]

### Added

- Initial Release
