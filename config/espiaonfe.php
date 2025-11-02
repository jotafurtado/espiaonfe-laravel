<?php

return [

    /*
    |--------------------------------------------------------------------------
    | EspiaOnfe API Configuration
    |--------------------------------------------------------------------------
    |
    | Este arquivo contém as configurações necessárias para integração com
    | a API do EspiaOnfe. Todas as configurações podem ser definidas através
    | de variáveis de ambiente no arquivo .env da aplicação.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Esp Cloud Token
    |--------------------------------------------------------------------------
    |
    | Token de autenticação do Esp Cloud utilizado para autenticar requisições
    | na API do EspiaOnfe. Este token deve ser mantido em segurança e não
    | deve ser compartilhado publicamente.
    |
    */

    'esp_cloud_token' => env('ESPIAONFE_CLOUD_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | User Token
    |--------------------------------------------------------------------------
    |
    | Token do usuário utilizado para autenticar requisições na API do
    | EspiaOnfe. Este token identifica o usuário específico que está fazendo
    | as requisições à API.
    |
    */

    'user_token' => env('ESPIAONFE_USER_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Base URI
    |--------------------------------------------------------------------------
    |
    | URL base da API do EspiaOnfe. Por padrão, aponta para a URL de produção
    | da API. Você pode sobrescrever este valor através da variável de
    | ambiente ESPIAONFE_BASE_URI caso precise apontar para um ambiente
    | diferente (ex: homologação, desenvolvimento).
    |
    */

    'base_uri' => env('ESPIAONFE_BASE_URI', 'https://api.espiaonfe.com.br'),

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | Tempo máximo em segundos para aguardar uma resposta da API.
    | Valor padrão: 30 segundos.
    |
    */

    'timeout' => env('ESPIAONFE_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Retry
    |--------------------------------------------------------------------------
    |
    | Número de tentativas em caso de falha na requisição.
    | Valor padrão: 3 tentativas.
    |
    */

    'retry' => env('ESPIAONFE_RETRY', 3),

    /*
    |--------------------------------------------------------------------------
    | Retry Delay
    |--------------------------------------------------------------------------
    |
    | Tempo em milissegundos entre tentativas de retry.
    | Valor padrão: 100ms.
    |
    */

    'retry_delay' => env('ESPIAONFE_RETRY_DELAY', 100),

    /*
    |--------------------------------------------------------------------------
    | Log Requests
    |--------------------------------------------------------------------------
    |
    | Se habilitado, registra todas as requisições HTTP no log da aplicação.
    | Útil para debug, mas pode gerar muitos logs em produção.
    | Valor padrão: false.
    |
    */

    'log_requests' => env('ESPIAONFE_LOG_REQUESTS', false),

];

