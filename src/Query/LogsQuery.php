<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class LogsQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/consulta/periodo/logs");
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
     * Filtra por tipo de log.
     */
    public function tipo(string $tipo): static
    {
        return $this->where("tipo", $tipo);
    }

    /**
     * Filtra por modelo do documento.
     * Modelos aceitos: 55 (NF-e) ou 57 (CT-e)
     *
     * @param string $modelo O modelo do documento fiscal
     * @return static
     * @throws \InvalidArgumentException Se o modelo for inválido
     */
    public function modelo(string $modelo): static
    {
        $modelosValidos = ["55", "57"];

        if (!in_array($modelo, $modelosValidos, true)) {
            throw new \InvalidArgumentException(
                "Modelo inválido: '{$modelo}'. Use: 55 (NF-e) ou 57 (CT-e)",
            );
        }

        return $this->where("modelo", $modelo);
    }

    /**
     * Filtra logs de NF-e (modelo 55).
     * Atalho para modelo('55')
     *
     * @return static
     */
    public function modeloNfe(): static
    {
        return $this->where("modelo", "55");
    }

    /**
     * Filtra logs de CT-e (modelo 57).
     * Atalho para modelo('57')
     *
     * @return static
     */
    public function modeloCte(): static
    {
        return $this->where("modelo", "57");
    }
}
