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

];

