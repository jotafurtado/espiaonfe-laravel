<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;
use Jcf\EspiaoNfe\Query\Concerns\HasCnpjCpf;
use Jcf\EspiaoNfe\Query\Concerns\HasPeriodo;

class ResgateXmlQuery extends QueryBuilder
{
    use HasCnpjCpf, HasPeriodo;

    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/resgatexml/consulta/resgatados");
    }

    /**
     * Insere chaves de acesso para resgate de XML.
     * Endpoint: /v1-cloud/resgatexml/chaves-acesso
     *
     * @param array<string, mixed> $data Dados com chaves de acesso
     * @return array<string, mixed> Resposta da API
     */
    public function inserirChaves(array $data): array
    {
        try {
            $response = $this->http->post(
                "/v1-cloud/resgatexml/chaves-acesso",
                $data,
            );
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }

    /**
     * Consulta o andamento do resgate de XML.
     * Endpoint: /v1-cloud/resgatexml/consulta/andamento
     *
     * @param string $cnpjCpf CNPJ/CPF da empresa
     * @param string $idRequisicao ID da requisição
     * @return array<string, mixed> Resposta da API
     */
    public function andamento(string $cnpjCpf, string $idRequisicao): array
    {
        try {
            $response = $this->http->get(
                "/v1-cloud/resgatexml/consulta/andamento",
                [
                    "cnpjCpf" => $cnpjCpf,
                    "idRequisicao" => $idRequisicao,
                ],
            );
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }

    /**
     * Consulta XMLs resgatados (usa o endpoint padrão).
     * Endpoint: /v1-cloud/resgatexml/consulta/resgatados
     *
     * @return static
     */
    public function resgatados(): static
    {
        // Já está no endpoint correto definido no construtor
        return $this;
    }
}
