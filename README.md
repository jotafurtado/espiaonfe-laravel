# Jcf/EspiaoNfe

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jcf/espiaonfe.svg?style=flat-square)](https://packagist.org/packages/jcf/espiaonfe)
[![Total Downloads](https://img.shields.io/packagist/dt/jcf/espiaonfe.svg?style=flat-square)](https://packagist.org/packages/jcf/espiaonfe)

Pacote Laravel para integração com a API do EspiãoNFe. Este pacote fornece uma interface simples e elegante para interagir com todos os endpoints da API EspiãoNFe, facilitando o gerenciamento de certificados digitais, empresas, NFes, CTes, NFSe e muito mais.

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

# Configurações avançadas (opcionais)
ESPIAONFE_TIMEOUT=30                    # Timeout das requisições em segundos (padrão: 30)
ESPIAONFE_RETRY=3                      # Número de tentativas automáticas (padrão: 3)
ESPIAONFE_RETRY_DELAY=100               # Delay entre tentativas em milissegundos (padrão: 100)
ESPIAONFE_LOG_REQUESTS=false           # Habilitar logging de requisições (padrão: false)
```

> **Nota**: O `ESPIAONFE_BASE_URI` é opcional e já possui um valor padrão (`https://api.espiaonfe.com.br`). Use apenas se precisar apontar para um ambiente diferente.

## Uso

O pacote fornece uma **API fluente estilo Laravel** que torna o uso extremamente elegante e intuitivo:

### ✨ Métodos Intuitivos

O pacote inclui métodos auto-explicativos que tornam o código mais limpo e fácil de entender:

#### Tipo de Período (XMLs)

```php
// Consulta pelo período de emissão do documento
->tipoPeriodoEmissao()

// Consulta pelo período de inclusão no Espião Cloud
->tipoPeriodoInclusao()

// Ou use diretamente (E = emissão, I = inclusão)
->tipoPeriodo('E')
->tipoPeriodo('I')
```

#### Modelos de Documentos

```php
// Métodos intuitivos para cada modelo
->modeloNfe()    // NF-e (modelo 55)
->modeloNfce()   // NFC-e (modelo 65)
->modeloCte()    // CT-e (modelo 57)
->modeloCteOs()  // CT-e OS (modelo 67)
->modeloSat()    // SAT (modelo 59)
->modeloNfse()   // NFS-e Nacional (modelo 41)

// Ou use diretamente com o código do modelo
->modelo('55') // 55, 65, 57, 67, 59, 41
```

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
    ->modeloNfe() // Método intuitivo para modelo 55 (NF-e)
    ->get();

// Consultar NFC-e (Nota Fiscal de Consumidor)
$nfceResumo = EspiaoNfe::nfe()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->modeloNfce() // Método intuitivo para modelo 65 (NFC-e)
    ->get();

// Consultar SAT (Sistema Autenticador e Transmissor)
$satResumo = EspiaoNfe::nfe()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->modeloSat() // Método intuitivo para modelo 59 (SAT)
    ->get();

// Ou usar modelo() diretamente
$nfeResumo = EspiaoNfe::nfe()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->modelo('55') // 55 (NF-e), 65 (NFC-e) ou 59 (SAT)
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
    ->modeloCte() // Método intuitivo para modelo 57 (CT-e)
    ->get();

// Ou usar modelo() diretamente
$cteResumo = EspiaoNfe::cte()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->modelo('57') // 57 (CT-e)
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
// Listar XMLs de NF-e por período de emissão
$xmls = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->tipoPeriodoEmissao() // Período de emissão
    ->modeloNfe() // Modelo 55 (NF-e)
    ->get();

// XMLs de NFC-e por período de inclusão
$xmls = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->tipoPeriodoInclusao() // Período de inclusão no sistema
    ->modeloNfce() // Modelo 65 (NFC-e)
    ->get();

// XMLs de CT-e
$xmls = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->tipoPeriodoEmissao()
    ->modeloCte() // Modelo 57 (CT-e)
    ->get();

// XMLs de CT-e OS (Outros Serviços)
$xmls = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->tipoPeriodoEmissao()
    ->modeloCteOs() // Modelo 67 (CT-e OS)
    ->get();

// XMLs de SAT
$xmls = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->tipoPeriodoEmissao()
    ->modeloSat() // Modelo 59 (SAT)
    ->get();

// XMLs de NFS-e Nacional
$xmls = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->tipoPeriodoInclusao()
    ->modeloNfse() // Modelo 41 (NFS-e Nacional)
    ->get();

// Ou usar tipoPeriodo() e modelo() diretamente
$xmls = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->tipoPeriodo('E') // E (emissão) ou I (inclusão)
    ->modelo('55') // 55, 65, 57, 67, 59, 41
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

##### 📌 Entendendo tipoPeriodoEmissao() vs tipoPeriodoInclusao()

Estes métodos definem como o período de consulta será interpretado:

- **`tipoPeriodoEmissao()`** - Consulta pela **data de emissão do documento**

  - Filtra XMLs pela data em que o documento foi **emitido pelo emissor**
  - Exemplo: NF-e emitida em 15/01/2024 será encontrada ao buscar o período de 01/01/2024 a 31/01/2024
  - **Use quando**: Você quer todos os documentos emitidos em um determinado período

- **`tipoPeriodoInclusao()`** - Consulta pela **data de inclusão no Espião Cloud**
  - Filtra XMLs pela data em que foram **recebidos/importados** no sistema Espião Cloud
  - Exemplo: NF-e emitida em 15/01/2024 mas incluída no sistema em 20/01/2024 será encontrada apenas no período que inclui 20/01/2024
  - **Use quando**: Você quer saber quais documentos foram adicionados ao sistema em um período específico

**Exemplo Prático:**

```php
// Cenário: Uma NF-e foi emitida em 10/01/2024, mas só foi incluída no Espião Cloud em 25/01/2024

// Por emissão - ENCONTRA a NF-e
$xmlsEmissao = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '15/01/2024')
    ->tipoPeriodoEmissao()
    ->modelo('55')
    ->get();

// Por inclusão - NÃO encontra (foi incluída depois do período)
$xmlsInclusao = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '15/01/2024')
    ->tipoPeriodoInclusao()
    ->modelo('55')
    ->get();

// Por inclusão - ENCONTRA a NF-e (período inclui 25/01/2024)
$xmlsInclusao = EspiaoNfe::xmls()
    ->cnpjCpf('12345678000190')
    ->periodo('20/01/2024', '31/01/2024')
    ->tipoPeriodoInclusao()
    ->modelo('55')
    ->get();
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
// Consultar logs de NF-e
$logs = EspiaoNfe::logs()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->modeloNfe() // Logs de NF-e (modelo 55)
    ->tipo('erro') // opcional
    ->get();

// Consultar logs de CT-e
$logs = EspiaoNfe::logs()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->modeloCte() // Logs de CT-e (modelo 57)
    ->get();

// Ou usar modelo() diretamente
$logs = EspiaoNfe::logs()
    ->cnpjCpf('12345678000190')
    ->periodo('01/01/2024', '31/01/2024')
    ->modelo('55') // 55 (NF-e) ou 57 (CT-e)
    ->get();

// Com paginação
$codigoProxima = $logs['codigoProximaPagina'];
if ($codigoProxima !== '-1') {
    $maisLogs = EspiaoNfe::logs()
        ->cnpjCpf('12345678000190')
        ->codigoProximaPagina($codigoProxima)
        ->modeloNfe()
        ->get();
}
```

## Validações e Tratamento de Erros

O pacote inclui validações automáticas e tratamento de erros robusto:

### Validações Automáticas

- **Formato de Data**: Valida formato DD/MM/AAAA e verifica se a data é válida
- **Ordem de Período**: Garante que a data inicial seja anterior ou igual à data final
- **CNPJ/CPF**: Valida formato básico (11 ou 14 dígitos, aceita formatação)
- **Modelos**: Valida modelos fiscais conforme contexto (NF-e, CT-e, etc.)

```php
// Validação automática de formato de data
try {
    EspiaoNfe::nfe()
        ->cnpjCpf('12345678000190')
        ->periodo('2024-01-01', '31/01/2024'); // Formato inválido
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage(); // "Formato de data inválido: '2024-01-01'. Use: DD/MM/AAAA"
}

// Validação de ordem de período
try {
    EspiaoNfe::nfe()
        ->cnpjCpf('12345678000190')
        ->periodo('31/01/2024', '01/01/2024'); // Data inicial depois da final
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage(); // "A data inicial (31/01/2024) deve ser anterior ou igual à data final (01/01/2024)"
}

// Validação de CNPJ/CPF
try {
    EspiaoNfe::nfe()->cnpjCpf('123'); // CNPJ/CPF inválido
} catch (\InvalidArgumentException $e) {
    echo $e->getMessage(); // "CNPJ/CPF inválido: deve ter 11 dígitos (CPF) ou 14 dígitos (CNPJ)"
}
```

### Exceções Específicas

O pacote fornece exceções específicas para diferentes tipos de erros HTTP:

```php
use Jcf\EspiaoNfe\Exceptions\AuthenticationException;
use Jcf\EspiaoNfe\Exceptions\NotFoundException;
use Jcf\EspiaoNfe\Exceptions\ValidationException;
use Jcf\EspiaoNfe\Exceptions\EspiaoNfeException;

try {
    $certificados = EspiaoNfe::certificados()->get();
} catch (AuthenticationException $e) {
    // Erro 401/403 - Problema de autenticação
    // Verifique se os tokens estão corretos
} catch (NotFoundException $e) {
    // Erro 404 - Recurso não encontrado
    // Verifique se o ID do recurso está correto
} catch (ValidationException $e) {
    // Erro 422 - Erro de validação
    // Verifique os dados enviados na requisição
} catch (EspiaoNfeException $e) {
    // Outros erros HTTP (500, 503, etc.)
    // Erro geral da API
}
```

## Configurações Avançadas

### Timeout e Retry

Configure timeout e retry automático para requisições:

```env
ESPIAONFE_TIMEOUT=60          # Timeout de 60 segundos
ESPIAONFE_RETRY=5             # 5 tentativas automáticas
ESPIAONFE_RETRY_DELAY=200     # 200ms entre tentativas
```

### Logging de Requisições

Para debug, você pode habilitar o logging de todas as requisições:

```env
ESPIAONFE_LOG_REQUESTS=true
```

Isso registrará no log do Laravel:

- Todas as requisições HTTP (método, URI, dados)
- Todas as respostas de erro (status, corpo da resposta)

## Changelog

Veja o [CHANGELOG](CHANGELOG.md) para obter informações sobre mudanças recentes.

## Licença

Este pacote é de código aberto e está licenciado sob a [Licença MIT](LICENSE.md).
