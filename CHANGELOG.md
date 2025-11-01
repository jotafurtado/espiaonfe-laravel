# Changelog

All notable changes to `jcf/espiaonfe` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.0.1] - 2024-11-01

### Added
- Initial development release
- Integration with EspiãoNFe API
- Fluent API for all endpoints
- Support for Certificados (Certificates)
- Support for Empresas (Companies)
- Support for NF-e (Electronic Invoice)
- Support for CT-e (Electronic Transport Document)
- Support for NFSe (Service Invoice)
- Support for XML and PDF management
- Support for XML rescue functionality
- Support for Logs
- Laravel Auto-discovery for Service Provider and Facade
- Comprehensive test suite (Unit and Feature tests)
- Configuration file with environment variables support
- Complete documentation in README

### Features
- **Certificates Management**: List, create, update, and delete digital certificates
- **Company Management**: List, create, update, and retrieve company information
- **NF-e Operations**: Get summaries and manifest electronic invoices
- **CT-e Operations**: Get summaries and register disagreements
- **NFSe Operations**: Consult by city and list approved cities
- **XML Management**: Import, retrieve by key, and generate PDFs
- **XML Rescue**: Insert keys, check progress, and retrieve rescued XMLs
- **Logging**: Retrieve system logs with filtering

### Technical
- PHP 8.1+ support
- Laravel 10.x, 11.x, and 12.x support
- PSR-4 autoloading
- PHPUnit test suite
- HTTP client abstraction with Laravel HTTP facade
- Comprehensive error handling with custom exceptions

[Unreleased]: https://github.com/jotacfurtado/espiaonfe-laravel/compare/v0.0.1...HEAD
[0.0.1]: https://github.com/jotacfurtado/espiaonfe-laravel/releases/tag/v0.0.1