# Changelog

All notable changes to `jcf/espiaonfe` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.0.4] - 2025-01-14

### Added

- ✅ **Validações robustas** - Validação automática de dados de entrada
  - Validação de formato de data (DD/MM/AAAA) com verificação de datas válidas
  - Validação de ordem de período (data inicial deve ser anterior ou igual à data final)
  - Validação básica de CNPJ/CPF (formato e tamanho)
  - Validação centralizada de modelos usando classe `Modelos` com constantes
- ✅ **Exceções específicas** - Tratamento de erros HTTP melhorado

  - `AuthenticationException` - Para erros 401/403 (autenticação)
  - `NotFoundException` - Para erros 404 (recurso não encontrado)
  - `ValidationException` - Para erros 422 (validação)
  - Melhor captura e conversão de exceções HTTP do Laravel

- ✅ **Configurações avançadas** - Mais controle sobre o comportamento do cliente

  - `ESPIAONFE_TIMEOUT` - Configura timeout das requisições (padrão: 30s)
  - `ESPIAONFE_RETRY` - Configura número de tentativas automáticas (padrão: 3)
  - `ESPIAONFE_RETRY_DELAY` - Configura delay entre tentativas em ms (padrão: 100ms)
  - `ESPIAONFE_LOG_REQUESTS` - Habilita logging de requisições para debug (padrão: false)

- ✅ **Traits reutilizáveis** - Eliminação de duplicação de código
  - `HasPeriodo` - Trait com métodos `dataInicial()`, `dataFinal()`, `periodo()` e validações
  - `HasCnpjCpf` - Trait com método `cnpjCpf()` e validação
- ✅ **Classe de constantes** - Centralização de modelos fiscais

  - `Jcf\EspiaoNfe\Constants\Modelos` - Classe com constantes para todos os modelos
  - Método `Modelos::validar()` para validação centralizada com mensagens contextuais

- ✅ **Melhorias no Client** - Cliente HTTP mais robusto

  - Timeout configurável por requisição
  - Retry automático com delay configurável
  - Logging opcional de requisições e erros
  - Validação melhorada de tokens (trim de espaços)

- ✅ **Melhorias no QueryBuilder** - Tratamento de erros aprimorado

  - Captura de exceções HTTP do Laravel e conversão para exceções específicas
  - Logging de erros quando habilitado
  - PHPDoc melhorado com tipos de retorno mais específicos

- ✅ **Suporte a respostas binárias** - Correção no método `pdfPorChave()`

  - `pdfPorChave()` agora retorna conteúdo binário (string) em vez de tentar parsear JSON
  - Tratamento adequado de erros para requisições binárias

- ✅ **Testes adicionais** - Maior cobertura de testes
  - `ValidationTest` - Testes de validação de dados de entrada
  - `ModelosConstantsTest` - Testes das constantes de modelos
  - `ErrorHandlingTest` - Testes de tratamento de erros HTTP

### Changed

- 🔄 **EmpresasQuery** - Corrigida inconsistência de nome de parâmetro

  - Alterado de `cnpj_cpf` para `cnpjCpf` para manter consistência com outras queries
  - Mantém compatibilidade com a API (não afeta funcionalidade)

- 🔄 **Todas as classes Query** - Refatoração para usar traits e constantes
  - Eliminação de código duplicado usando traits `HasPeriodo` e `HasCnpjCpf`
  - Uso de constantes `Modelos` em vez de strings hardcoded
  - Código mais limpo e manutenível

### Fixed

- 🐛 Correção na validação de tokens vazios (agora usa `trim()`)
- 🐛 Correção no tratamento de exceções HTTP que não eram capturadas corretamente
- 🐛 Correção no método `pdfPorChave()` para retornar conteúdo binário

### Improved

- 📝 PHPDoc melhorado em todos os métodos com tipos de retorno mais específicos
- 📝 Mensagens de erro mais descritivas e contextuais
- 📝 Validações fornecem mensagens de erro claras indicando o problema específico

**Exemplo de uso das novas validações:**

```php
// Validação automática de formato de data
try {
    EspiaoNfe::nfe()
        ->cnpjCpf('12345678000190')
        ->periodo('2024-01-01', '31/01/2024'); // ❌ Formato inválido
} catch (\InvalidArgumentException $e) {
    // "Formato de data inválido: '2024-01-01'. Use: DD/MM/AAAA"
}

// Validação de ordem de período
try {
    EspiaoNfe::nfe()
        ->cnpjCpf('12345678000190')
        ->periodo('31/01/2024', '01/01/2024'); // ❌ Data inicial depois da final
} catch (\InvalidArgumentException $e) {
    // "A data inicial (31/01/2024) deve ser anterior ou igual à data final (01/01/2024)"
}

// Validação de CNPJ/CPF
try {
    EspiaoNfe::nfe()->cnpjCpf('123'); // ❌ CNPJ/CPF inválido
} catch (\InvalidArgumentException $e) {
    // "CNPJ/CPF inválido: deve ter 11 dígitos (CPF) ou 14 dígitos (CNPJ). Fornecido: 3 dígitos"
}

// Tratamento de erros HTTP específicos
try {
    EspiaoNfe::certificados()->get();
} catch (\Jcf\EspiaoNfe\Exceptions\AuthenticationException $e) {
    // Erro 401/403 - Problema de autenticação
} catch (\Jcf\EspiaoNfe\Exceptions\NotFoundException $e) {
    // Erro 404 - Recurso não encontrado
} catch (\Jcf\EspiaoNfe\Exceptions\ValidationException $e) {
    // Erro 422 - Erro de validação
}
```

**Configuração avançada (.env):**

```env
ESPIAONFE_CLOUD_TOKEN=seu_token_aqui
ESPIAONFE_USER_TOKEN=seu_token_aqui
ESPIAONFE_BASE_URI=https://api.espiaonfe.com.br
ESPIAONFE_TIMEOUT=60                    # Timeout de 60 segundos
ESPIAONFE_RETRY=5                       # 5 tentativas
ESPIAONFE_RETRY_DELAY=200               # 200ms entre tentativas
ESPIAONFE_LOG_REQUESTS=true             # Habilitar logging
```

## [0.0.3] - 2025-11-02

### Added

- ✅ Métodos `tipoPeriodoEmissao()` e `tipoPeriodoInclusao()` para XMLs - API mais intuitiva e amigável

  - `tipoPeriodoEmissao()` - Consulta XMLs pelo período de emissão do documento
  - `tipoPeriodoInclusao()` - Consulta XMLs pelo período de inclusão no Espião Cloud
  - Mantém compatibilidade com `tipoPeriodo('E')` e `tipoPeriodo('I')`

- ✅ Métodos auxiliares de modelo para todas as queries - API mais intuitiva e amigável

  **NfeQuery:**

  - `modeloNfe()` - Consulta NF-e (modelo 55)
  - `modeloNfce()` - Consulta NFC-e (modelo 65)
  - `modeloSat()` - Consulta SAT (modelo 59)

  **CteQuery:**

  - `modeloCte()` - Consulta CT-e (modelo 57)

  **XmlsQuery:**

  - `modeloNfe()` - Consulta XMLs de NF-e (modelo 55)
  - `modeloNfce()` - Consulta XMLs de NFC-e (modelo 65)
  - `modeloCte()` - Consulta XMLs de CT-e (modelo 57)
  - `modeloCteOs()` - Consulta XMLs de CT-e OS (modelo 67)
  - `modeloSat()` - Consulta XMLs de SAT (modelo 59)
  - `modeloNfse()` - Consulta XMLs de NFS-e Nacional (modelo 41)

  **LogsQuery:**

  - `modeloNfe()` - Consulta logs de NF-e (modelo 55)
  - `modeloCte()` - Consulta logs de CT-e (modelo 57)

  Todos os métodos incluem validação automática e mantêm compatibilidade com `modelo('XX')`

**Exemplo de uso:**

```php
// Mais intuitivo e legível
$xmls = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->tipoPeriodoEmissao() // Consulta pelo período de emissão
    ->modelo('55')
    ->get();

// Ou para consultar pelo período de inclusão
$xmls = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->tipoPeriodoInclusao() // Consulta pelo período de inclusão
    ->modelo('55')
    ->get();
```

**Exemplo de uso (Logs):**

```php
// Mais intuitivo e legível
$logs = EspiaoNfe::logs()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->modeloNfe() // Consulta logs de NF-e
    ->get();

// Ou para consultar logs de CT-e
$logs = EspiaoNfe::logs()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->modeloCte() // Consulta logs de CT-e
    ->get();
```

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

[Unreleased]: https://github.com/jotacfurtado/espiaonfe-laravel/compare/v0.0.4...HEAD
[0.0.4]: https://github.com/jotacfurtado/espiaonfe-laravel/compare/v0.0.3...v0.0.4
[0.0.3]: https://github.com/jotacfurtado/espiaonfe-laravel/compare/v0.0.2...v0.0.3
[0.0.2]: https://github.com/jotacfurtado/espiaonfe-laravel/compare/v0.0.1...v0.0.2
[0.0.1]: https://github.com/jotacfurtado/espiaonfe-laravel/releases/tag/v0.0.1
