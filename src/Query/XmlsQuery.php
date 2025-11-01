<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class XmlsQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/consulta/periodo/xmls");
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
     * Define o tipo de período (emissao ou inclusao).
     */
    public function tipoPeriodo(string $tipo): static
    {
        return $this->where("tipoPeriodo", $tipo);
    }

    /**
     * Obtém XML por chave de acesso.
     * Endpoint: /v1-cloud/consulta/chave/xml
     */
    public function porChave(string $chave): array
    {
        $response = $this->http->get("/v1-cloud/consulta/chave/xml", [
            "chave" => $chave,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Obtém PDF por chave de acesso.
     * Endpoint: /v1-cloud/consulta/chave/pdf
     */
    public function pdfPorChave(string $chave): array
    {
        $response = $this->http->get("/v1-cloud/consulta/chave/pdf", [
            "chave" => $chave,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Importa um XML.
     * Endpoint: /v1-cloud/importar/xml
     */
    public function importar(array $data): array
    {
        $response = $this->http->post("/v1-cloud/importar/xml", $data);

        return $this->handleResponse($response);
    }
}
