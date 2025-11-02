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
     * Define o modelo da NF-e.
     * Modelos aceitos: 55 (NF-e), 65 (NFC-e), 59 (SAT)
     *
     * @param string $modelo O modelo do documento fiscal
     * @return static
     * @throws \InvalidArgumentException Se o modelo for inválido
     */
    public function modelo(string $modelo): static
    {
        $modelosValidos = ["55", "65", "59"];

        if (!in_array($modelo, $modelosValidos, true)) {
            throw new \InvalidArgumentException(
                "Modelo inválido: '{$modelo}'. Use: 55 (NF-e), 65 (NFC-e) ou 59 (SAT)",
            );
        }

        return $this->where("modelo", $modelo);
    }

    /**
     * Filtra por NF-e (modelo 55).
     * Atalho para modelo('55')
     *
     * @return static
     */
    public function modeloNfe(): static
    {
        return $this->where("modelo", "55");
    }

    /**
     * Filtra por NFC-e (modelo 65).
     * Atalho para modelo('65')
     *
     * @return static
     */
    public function modeloNfce(): static
    {
        return $this->where("modelo", "65");
    }

    /**
     * Filtra por SAT (modelo 59).
     * Atalho para modelo('59')
     *
     * @return static
     */
    public function modeloSat(): static
    {
        return $this->where("modelo", "59");
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
