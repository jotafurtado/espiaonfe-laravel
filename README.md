# Jcf/EspiaoNfe

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

### API Fluente ✨

O pacote utiliza query builders que permitem encadear métodos de forma fluente, similar ao Eloquent do Laravel:

#### Exemplo: Obter Empresas com Paginação

```php
use Jcf\EspiaoNfe\Facades\EspiaoNfe;

// Listar empresas (limite de 100 por página)
$empresas = EspiaoNfe::empresas()->get();

// Navegar para próxima página usando o código retornado
$codigoProxima = $empresas['codigoProximaPagina']; // Ex: "200"
if ($codigoProxima !== '-1') {
    $empresas = EspiaoNfe::empresas()
        ->codigoProximaPagina($codigoProxima)
        ->get();
}

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
// Listar certificados (até 100 por página)
$certificados = EspiaoNfe::certificados()->get();

// Filtrar por serial
$certificado = EspiaoNfe::certificados()
    ->serial('4CAF1032F8C90902C81751D30FE47152')
    ->get();

// Criar certificado (multipart/form-data - requer arquivo)
$certificado = EspiaoNfe::certificados()->create([
    'arquivoCertificado' => /* arquivo .pfx */,
    'senha' => 'senha_do_certificado',
]);

// Atualizar certificado
$certificado = EspiaoNfe::certificados()
    ->find('4CAF1032F8C90902C81751D30FE47152')
    ->update([/* dados */]);

// Deletar certificado
EspiaoNfe::certificados()
    ->find('4CAF1032F8C90902C81751D30FE47152')
    ->delete(['comando' => 'cancelar']);
```

#### Exemplo: NF-e, CT-e e NFSe

```php
// Consultar NF-e por período (até 100 por página)
$nfeResumo = EspiaoNfe::nfe()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->get();

// Ou usar dataInicial e dataFinal separadamente
$nfeResumo = EspiaoNfe::nfe()
    ->cnpjCpf('12345678000190')
    ->dataInicial('01/01/2024')
    ->dataFinal('31/01/2024')
    ->get();

// Manifestar NF-e
$resultado = EspiaoNfe::nfe()->manifestar([
    'chave' => '35191234567890123456789012345678901234567890',
    'tipo' => '210200',
]);

// Consultar CT-e por período (até 100 por página)
$cteResumo = EspiaoNfe::cte()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->get();

// Desacordo de CT-e
$resultado = EspiaoNfe::cte()->desacordo([
    'chave' => '35191234567890123456789012345678901234567890',
    'motivo' => 'Mercadoria não recebida',
]);

// Consultar NFSe por período (até 100 por página)
$nfseResumo = EspiaoNfe::nfse()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->get();

// NFSe por cidade
$nfsePorCidade = EspiaoNfe::nfse()->porCidade([
    'cnpjCpf' => '12345678000190',
    'cidade' => '3550308',
    'dataInicial' => '01/01/2024',
    'dataFinal' => '31/01/2024',
]);

// Cidades homologadas
$cidades = EspiaoNfe::nfse()->cidadesHomologadas();
```

#### Exemplo: XMLs e PDFs

```php
// Listar XMLs por período (até 50 por página)
$xmls = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->tipoPeriodo('emissao') // ou 'inclusao'
    ->get();

// Obter XML por chave
$xml = EspiaoNfe::xmls()->porChave('35191234567890123456789012345678901234567890');

// Obter PDF por chave
$pdf = EspiaoNfe::xmls()->pdfPorChave('35191234567890123456789012345678901234567890');

// Importar XML
$resultado = EspiaoNfe::xmls()->importar([
    'xml' => '<?xml version="1.0"...',
    'cnpjCpf' => '12345678000190',
]);
```

#### Exemplo: Resgate de XML

```php
// Inserir chaves para resgate
$resultado = EspiaoNfe::resgateXml()->inserirChaves([
    'cnpjCpf' => '12345678000190',
    'chaves' => [
        '35191234567890123456789012345678901234567890',
        '35191234567890123456789012345678901234567891',
    ],
]);

// Consultar andamento
$andamento = EspiaoNfe::resgateXml()->andamento(
    '12345678000190',
    'ID_REQUISICAO_123'
);

// Consultar XMLs resgatados
$resgatados = EspiaoNfe::resgateXml()
    ->resgatados()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->get();

// Paginação
$codigoProxima = $resgatados['codigoProximaPagina'];
if ($codigoProxima !== '-1') {
    $maisResgatados = EspiaoNfe::resgateXml()
        ->resgatados()
        ->cnpjCpf('12345678000190')
        ->codigoProximaPagina($codigoProxima)
        ->get();
}
```

#### Exemplo: Logs

```php
// Consultar logs por período
$logs = EspiaoNfe::logs()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->tipo('erro') // opcional
    ->modelo('55') // opcional (55=NF-e, 57=CT-e, etc)
    ->get();

// Com paginação
$codigoProxima = $logs['codigoProximaPagina'];
if ($codigoProxima !== '-1') {
    $maisLogs = EspiaoNfe::logs()
        ->cnpjCpf('12345678000190')
        ->codigoProximaPagina($codigoProxima)
        ->get();
}
```

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

## Licença

Este pacote é de código aberto e está licenciado sob a [Licença MIT](LICENSE.md).
