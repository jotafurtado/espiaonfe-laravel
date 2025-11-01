<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class ResgateXmlQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/resgatexml/consulta/resgatados");
    }

    /**
     * Filtra por CNPJ/CPF da empresa.
     */
    public function cnpjCpf(string $cnpjCpf): static
    {
        return $this->where("cnpjCpf", $cnpjCpf);
    }

    /**
     * Define a data inicial (formato: DD/MM/AAAA).
     */
    public function dataInicial(string $dataInicial): static
    {
        return $this->where("dataInicial", $dataInicial);
    }

    /**
     * Define a data final (formato: DD/MM/AAAA).
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
     * Insere chaves de acesso para resgate de XML.
     * Endpoint: /v1-cloud/resgatexml/chaves-acesso
     */
    public function inserirChaves(array $data): array
    {
        $response = $this->http->post(
            "/v1-cloud/resgatexml/chaves-acesso",
            $data,
        );

        return $this->handleResponse($response);
    }

    /**
     * Consulta o andamento do resgate de XML.
     * Endpoint: /v1-cloud/resgatexml/consulta/andamento
     */
    public function andamento(string $cnpjCpf, string $idRequisicao): array
    {
        $response = $this->http->get(
            "/v1-cloud/resgatexml/consulta/andamento",
            [
                "cnpjCpf" => $cnpjCpf,
                "idRequisicao" => $idRequisicao,
            ],
        );

        return $this->handleResponse($response);
    }

    /**
     * Consulta XMLs resgatados (usa o endpoint padrão).
     * Endpoint: /v1-cloud/resgatexml/consulta/resgatados
     */
    public function resgatados(): static
    {
        // Já está no endpoint correto definido no construtor
        return $this;
    }
}
