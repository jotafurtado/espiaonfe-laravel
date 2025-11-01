<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class NfeQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/consulta/periodo/nfe-resumo");
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
     * Manifesta uma NF-e.
     * Endpoint: /v1-cloud/manifestacao/nfe/manifestar
     */
    public function manifestar(array $data): array
    {
        $response = $this->http->post(
            "/v1-cloud/manifestacao/nfe/manifestar",
            $data,
        );

        return $this->handleResponse($response);
    }
}
