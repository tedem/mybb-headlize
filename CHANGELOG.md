# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.2.1] - 2025-12-16

### Changed

- Optimize PHP version check for improved clarity and performance.

## [1.2.0] - 2025-12-15

### Added

- Added compatibility with MyBB 1.9.

### Changed

- Minimum PHP version requirement is now 8.2.

## [1.1.0] - 2025-12-14

### Added

- Prevent execution on PHP versions below 7.4.
- Preserve title case exceptions during word conversion.
- Add translation helper function.

### Changed

- Update `$smallWords` for APA 7 and TDK compliance.
- Streamline plugin description handling.

### Fixed

- Add hook to convert title when inserting posts.
- Escape title output to prevent SQL injection.
- Extend trailing dot removal to support multiple dots.
- Remove `str_ends_with()` for PHP 7.4 compatibility.
- Remove reply prefix handling from title conversion.

## [1.0.1] - 2025-02-14

### Changed

- Updated Turkish small words.

## [1.0.0] - 2025-02-12

First release!
