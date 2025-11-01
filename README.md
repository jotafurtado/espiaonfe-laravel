# Jcf EspiaoNfe

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jcf/espiaonfe.svg?style=flat-square)](https://packagist.org/packages/jcf/espiaonfe)
[![Total Downloads](https://img.shields.io/packagist/dt/jcf/espiaonfe.svg?style=flat-square)](https://packagist.org/packages/jcf/espiaonfe)

Pacote Laravel para integração com a API do EspiaOnfe. Este pacote fornece uma interface simples e elegante para interagir com todos os endpoints da API EspiaOnfe, facilitando o gerenciamento de certificados digitais, empresas, NFes, CTes, NFSe e muito mais.

## Instalação

Você pode instalar o pacote via Composer:

```bash
composer require jcf/espiaonfe
```

## Configuração

Após instalar o pacote, publique o arquivo de configuração:

```bash
php artisan vendor:publish --tag=config --provider="Jcf\EspiaoNfe\Providers\EspiaoNfeServiceProvider"
```

Isso criará o arquivo `config/espiaonfe.php` em sua aplicação.

Em seguida, configure as variáveis de ambiente no seu arquivo `.env`:

```env
ESPIAONFE_CLOUD_TOKEN=seu_esp_cloud_token_aqui
ESPIAONFE_USER_TOKEN=seu_user_token_aqui
ESPIAONFE_BASE_URI=https://api.espiaonfe.com.br
```

> **Nota**: O `ESPIAONFE_BASE_URI` é opcional e já possui um valor padrão (`https://api.espiaonfe.com.br`). Use apenas se precisar apontar para um ambiente diferente.

## Uso

O pacote fornece uma **API fluente estilo Laravel** que torna o uso extremamente elegante e intuitivo:

### API Fluente (Recomendado) ✨

O pacote utiliza query builders que permitem encadear métodos de forma fluente, similar ao Eloquent do Laravel:

#### Exemplo: Obter Empresas com Paginação

```php
use Jcf\EspiaoNfe\Facades\EspiaoNfe;

// Listar todas as empresas
$empresas = EspiaoNfe::empresas()->get();

// Com paginação
$empresas = EspiaoNfe::empresas()
    ->pagina(1)
    ->limite(10)
    ->get();

// Filtrar por CNPJ/CPF
$empresa = EspiaoNfe::empresas()
    ->cnpjCpf('12345678000190')
    ->get();

// Criar uma nova empresa
$empresa = EspiaoNfe::empresas()->create([
    'cnpj' => '12345678000190',
    'razao_social' => 'Minha Empresa LTDA',
]);

// Atualizar uma empresa
$empresa = EspiaoNfe::empresas()
    ->find('12345678000190')
    ->update(['razao_social' => 'Nova Razão Social']);
```

#### Exemplo: Trabalhar com Certificados

```php
// Listar certificados
$certificados = EspiaoNfe::certificados()->get();

// Criar certificado
$certificado = EspiaoNfe::certificados()->create([
    'serial' => '123456789',
    // ... outros campos
]);

// Atualizar certificado
$certificado = EspiaoNfe::certificados()
    ->find('123456789')
    ->update([/* dados */]);

// Deletar certificado
EspiaoNfe::certificados()
    ->find('123456789')
    ->delete(['comando' => 'cancelar']);
```

#### Exemplo: NF-e, CT-e e NFSe

```php
// Resumo de NF-e com paginação
$nfeResumo = EspiaoNfe::nfe()
    ->resumo()
    ->pagina(1)
    ->limite(20)
    ->get();

// Manifestar NF-e
$resultado = EspiaoNfe::nfe()->manifestar([
    'chave' => '35191234567890123456789012345678901234567890',
    'tipo' => '210200',
]);

// Resumo de CT-e
$cteResumo = EspiaoNfe::cte()
    ->resumo()
    ->pagina(1)
    ->get();

// Desacordo de CT-e
$resultado = EspiaoNfe::cte()->desacordo([
    'chave' => '...',
    'motivo' => '...',
]);

// NFSe por cidade
$nfsePorCidade = EspiaoNfe::nfse()->porCidade([
    'cidade' => '3550308',
    'data_inicio' => '2024-01-01',
]);

// Cidades homologadas
$cidades = EspiaoNfe::nfse()->cidadesHomologadas();
```

#### Exemplo: XMLs e PDFs

```php
// Listar XMLs
$xmls = EspiaoNfe::xmls()
    ->pagina(1)
    ->get();

// Obter XML por chave
$xml = EspiaoNfe::xmls()->porChave('35191234567890123456789012345678901234567890');

// Obter PDF por chave
$pdf = EspiaoNfe::xmls()->pdfPorChave('35191234567890123456789012345678901234567890');

// Importar XML
$resultado = EspiaoNfe::xmls()->importar([
    'xml' => '...',
    'cnpj' => '12345678000190',
]);
```

#### Exemplo: Resgate de XML

```php
// Inserir chaves para resgate
$resultado = EspiaoNfe::resgateXml()->inserirChaves([
    'chaves' => ['35191234567890123456789012345678901234567890'],
]);

// Consultar andamento
$andamento = EspiaoNfe::resgateXml()->andamento([
    'id' => '12345',
]);

// Consultar resgatados
$resgatados = EspiaoNfe::resgateXml()
    ->resgatados()
    ->pagina(1)
    ->get();
```

#### Exemplo: Logs

```php
$logs = EspiaoNfe::logs()
    ->where('tipo', 'erro')
    ->pagina(1)
    ->get();
```

### Métodos Legados (Compatibilidade)

Os métodos antigos ainda estão disponíveis para compatibilidade:

```php
$certificados = EspiaoNfe::getCertificados();
$empresas = EspiaoNfe::getEmpresas(['pagina' => 1]);
$xml = EspiaoNfe::getXmlByChave(['chave' => '...']);
```

## Métodos Disponíveis

O pacote implementa todos os endpoints da API EspiaOnfe:

### Certificados
- `getCertificados()` - Lista todos os certificados
- `createCertificado(array $data)` - Cria um novo certificado
- `updateCertificado(string $serial, array $data)` - Atualiza um certificado
- `deleteCertificado(string $serial, string $comando)` - Remove um certificado

### Empresas
- `getEmpresas(array $params = [])` - Lista empresas
- `createEmpresa(array $data)` - Cria uma nova empresa
- `getEmpresa(array $params)` - Obtém dados de uma empresa específica
- `updateEmpresa(string $cnpjCpf, array $data)` - Atualiza uma empresa

### Notas Fiscais (NF-e)
- `getNfeResumo(array $params)` - Obtém resumo de NF-e
- `manifestarNfe(array $data)` - Manifesta uma NF-e

### Conhecimentos de Transporte (CT-e)
- `getCteResumo(array $params)` - Obtém resumo de CT-e
- `desacordoCte(array $data)` - Registra desacordo de CT-e

### Notas Fiscais de Serviços (NFSe)
- `getNfseResumo(array $params)` - Obtém resumo de NFSe
- `consultarNfsePorCidade(array $params)` - Consulta NFSe por cidade
- `getCidadesHomologadas()` - Lista cidades homologadas

### XMLs e PDFs
- `getXmls(array $params)` - Lista XMLs
- `getXmlByChave(array $params)` - Obtém XML por chave
- `getPdfByChave(array $params)` - Obtém PDF por chave
- `importarXml(array $data)` - Importa um XML

### Logs
- `getLogs(array $params)` - Obtém logs do sistema

### Resgate de XML
- `inserirChavesResgateXml(array $data)` - Insere chaves para resgate de XML
- `consultarAndamentoResgateXml(array $params)` - Consulta andamento do resgate
- `consultarResgatados(array $params)` - Consulta XMLs resgatados

## Testes

Execute os testes do pacote usando PHPUnit:

```bash
cd packages/Jcf/EspiaoNfe
vendor/bin/phpunit
```

Ou execute uma suite específica:

```bash
vendor/bin/phpunit --testsuite=Unit
vendor/bin/phpunit --testsuite=Feature
```

## Changelog

Veja o [CHANGELOG](CHANGELOG.md) para obter informações sobre mudanças recentes.

## Contribuindo

Contribuições são bem-vindas! Por favor, leia o [Guia de Contribuição](CONTRIBUTING.md) antes de enviar pull requests.

## Créditos

- [João C. Furtado](https://github.com/seu-usuario)

## Licença

Este pacote é de código aberto e está licenciado sob a [Licença MIT](LICENSE.md).

