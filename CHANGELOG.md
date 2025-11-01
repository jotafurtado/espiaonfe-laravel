# Changelog

All notable changes to `jcf/espiaonfe` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.0.2] - 2024-11-01

### Changed - BREAKING CHANGES ⚠️

Esta versão corrige os endpoints da API para corresponder exatamente com a documentação oficial do EspiãoNFe.

**Endpoints corrigidos:**
- ❌ `/v1-cloud/nfe/resumo` → ✅ `/v1-cloud/consulta/periodo/nfe-resumo`
- ❌ `/v1-cloud/cte/resumo` → ✅ `/v1-cloud/consulta/periodo/cte-resumo`
- ❌ `/v1-cloud/nfse/resumo` → ✅ `/v1-cloud/consulta/periodo/nfse-resumo`
- ❌ `/v1-cloud/xmls` → ✅ `/v1-cloud/consulta/periodo/xmls`
- ❌ `/v1-cloud/logs` → ✅ `/v1-cloud/consulta/periodo/logs`
- ❌ `/v1-cloud/nfe/manifestar` → ✅ `/v1-cloud/manifestacao/nfe/manifestar`
- ❌ `/v1-cloud/cte/desacordo` → ✅ `/v1-cloud/manifestacao/cte/desacordo`
- ❌ `/v1-cloud/xmls/resgate/*` → ✅ `/v1-cloud/resgatexml/*`

**Métodos removidos:**
- ❌ `nfe()->resumo()` - Use `nfe()->get()` diretamente
- ❌ `cte()->resumo()` - Use `cte()->get()` diretamente
- ❌ `nfse()->resumo()` - Use `nfse()->get()` diretamente

**Novos métodos adicionados:**
- ✅ `cnpjCpf()` - Filtra por CNPJ/CPF em todos os endpoints de consulta
- ✅ `dataInicial()` e `dataFinal()` - Define período de consulta
- ✅ `periodo()` - Atalho para definir data inicial e final
- ✅ `tipoPeriodo()` - Para XMLs (emissao ou inclusao)
- ✅ `tipo()` - Filtro de logs por tipo
- ✅ `modelo()` - Filtro de logs por modelo de documento

**Migração necessária:**

```php
// ANTES (v0.0.1)
$nfe = EspiaoNfe::nfe()->resumo()->limite(5)->get();

// DEPOIS (v0.0.2)
$nfe = EspiaoNfe::nfe()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->get();
```

### Added
- Métodos de filtro por data em todos os Query Builders de consulta
- Método `periodo()` como atalho para `dataInicial()` + `dataFinal()`
- Suporte completo à estrutura de dados real da API (baseado em Swagger)
- Documentação completa com exemplos reais

### Fixed
- Endpoints agora correspondem 100% com a API oficial do EspiãoNFe
- Estrutura de resposta agora usa `dados` (correto) em vez de `data`
- Paginação agora usa `codigoProximaPagina` conforme documentação

## [0.0.1] - 2024-11-01

### Added
- Initial development release
- Integration with EspiãoNFe API
- Fluent API for all endpoints (query builders)
- Clean API design without legacy methods
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
- Pagination support using `codigoProximaPagina()` method (matches API behavior)

### Features
- **Certificates Management**: List, create, update, and delete digital certificates
- **Company Management**: List, create, update, and retrieve company information (100 items per page)
- **NF-e Operations**: Get summaries and manifest electronic invoices
- **CT-e Operations**: Get summaries and register disagreements
- **NFSe Operations**: Consult by city and list approved cities
- **XML Management**: Import, retrieve by key, and generate PDFs
- **XML Rescue**: Insert keys, check progress, and retrieve rescued XMLs
- **Logging**: Retrieve system logs with filtering
- **Smart Pagination**: Navigate through pages using API's native `codigoProximaPagina`

### Technical
- PHP 8.1+ support
- Laravel 10.x, 11.x, and 12.x support
- PSR-4 autoloading
- PHPUnit test suite
- HTTP client abstraction with Laravel HTTP facade
- Comprehensive error handling with custom exceptions

[Unreleased]: https://github.com/jotacfurtado/espiaonfe-laravel/compare/v0.0.2...HEAD
[0.0.2]: https://github.com/jotacfurtado/espiaonfe-laravel/compare/v0.0.1...v0.0.2
[0.0.1]: https://github.com/jotacfurtado/espiaonfe-laravel/releases/tag/v0.0.1