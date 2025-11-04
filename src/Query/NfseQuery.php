<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;
use Jcf\EspiaoNfe\Query\Concerns\HasCnpjCpf;
use Jcf\EspiaoNfe\Query\Concerns\HasPeriodo;

class NfseQuery extends QueryBuilder
{
    use HasCnpjCpf, HasPeriodo;

    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/consulta/periodo/nfse-resumo");
    }

    /**
     * Consulta NFSe por cidade.
     * Endpoint: /v1-cloud/nfse/consulta/cidade
     *
     * @param array<string, mixed> $params Parâmetros da consulta
     * @return array<string, mixed> Resposta da API
     */
    public function porCidade(array $params): array
    {
        try {
            $response = $this->http->get(
                "/v1-cloud/nfse/consulta/cidade",
                $params,
            );
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }

    /**
     * Retorna as cidades homologadas para NFSe.
     * Endpoint: /v1-cloud/nfse/cidades
     *
     * @return array<string, mixed> Resposta da API
     */
    public function cidadesHomologadas(): array
    {
        try {
            $response = $this->http->get("/v1-cloud/nfse/cidades");
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }
}
