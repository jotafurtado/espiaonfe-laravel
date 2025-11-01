<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class NfseQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/consulta/periodo/nfse-resumo");
    }

    /**
     * Filtra por CNPJ/CPF da empresa.
     */
    public function cnpjCpf(string $cnpjCpf): static
    {
        return $this->where("cnpjCpf", $cnpjCpf);
    }

    /**
     * Define a data inicial de emissão (formato: DD/MM/AAAA).
     */
    public function dataInicial(string $dataInicial): static
    {
        return $this->where("dataInicial", $dataInicial);
    }

    /**
     * Define a data final de emissão (formato: DD/MM/AAAA).
     */
    public function dataFinal(string $dataFinal): static
    {
        return $this->where("dataFinal", $dataFinal);
    }

    /**
     * Define o período de consulta.
     */
    public function periodo(string $dataInicial, string $dataFinal): static
    {
        return $this->dataInicial($dataInicial)->dataFinal($dataFinal);
    }

    /**
     * Consulta NFSe por cidade.
     * Endpoint: /v1-cloud/nfse/consulta/cidade
     */
    public function porCidade(array $params): array
    {
        $response = $this->http->get("/v1-cloud/nfse/consulta/cidade", $params);

        return $this->handleResponse($response);
    }

    /**
     * Retorna as cidades homologadas para NFSe.
     * Endpoint: /v1-cloud/nfse/cidades
     */
    public function cidadesHomologadas(): array
    {
        $response = $this->http->get("/v1-cloud/nfse/cidades");

        return $this->handleResponse($response);
    }
}
