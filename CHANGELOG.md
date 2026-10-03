# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.1] - 2025-12-16

### Changed

- Optimized the PHP version check for improved clarity and performance.

## [2.0.0] - 2025-12-15

### Added

- Added MyBB 1.9 compatibility.

### Changed

- Increased the minimum required PHP version from 7.4 to 8.2.

## [1.1.0] - 2025-12-14

### Added

- Prevented execution on PHP versions below 7.4.
- Preserved title case exceptions during word conversion.
- Added a translation helper function.

### Changed

- Updated `$smallWords` for APA 7 and TDK compliance.
- Streamlined plugin description handling.

### Fixed

- Added a hook to convert titles when inserting posts.
- Escaped title output to prevent SQL injection.
- Extended trailing dot removal to support multiple dots.
- Removed `str_ends_with()` for PHP 7.4 compatibility.
- Removed reply prefix handling from title conversion.

## [1.0.1] - 2025-02-14

### Changed

- Updated Turkish small words.

## [1.0.0] - 2025-02-12

First release!
