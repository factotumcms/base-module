# Changelog

All notable changes to this project will be documented in this file.

## [1.4.1] - 2025-10-18
### Changed
- Refactor media_id to avatar in users table migration

## [1.4.0] - 2025-10-17
### Updated
- General refactoring and code optimizations
- Changed apis routes prefix and removing context
- Removed make() methods from dtos and use constructors instead

## [1.3.2] - 2025-10-16
### Updated
- Refactor relation name from profile_picture to avatar

## [1.3.1] - 2025-10-10
### Updated
- Refactor store method on mediaService to return Media model instance.
### Removed
- Removed config/auth-providers.php

## [1.3.0] - 2025-10-09
### Added
- Implemented orchestra for feature testing
- Added enum, model and dto unit tests
- Added github action for running test on pull request
### Updated
- Updated readme file

## [1.2.0] - 2025-10-08
### Updated
- Changed media conversions logic.
- Removed filterable trait
- Change migration file name

### Added
- Added media conversions path setting.

## [1.1.0] - 2025-10-07
### Added
- Backoffice register user functionality.


## [1.0.0] - 2025-10-06
### Added
- Application initial release.
