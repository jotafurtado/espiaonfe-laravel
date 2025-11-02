<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;
use Jcf\EspiaoNfe\Constants\Modelos;
use Jcf\EspiaoNfe\Query\Concerns\HasCnpjCpf;
use Jcf\EspiaoNfe\Query\Concerns\HasPeriodo;

class NfeQuery extends QueryBuilder
{
    use HasCnpjCpf, HasPeriodo;

    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/consulta/periodo/nfe-resumo");
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
        Modelos::validar($modelo, [Modelos::NFE, Modelos::NFCE, Modelos::SAT], 'NF-e');

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
        return $this->where("modelo", Modelos::NFE);
    }

    /**
     * Filtra por NFC-e (modelo 65).
     * Atalho para modelo('65')
     *
     * @return static
     */
    public function modeloNfce(): static
    {
        return $this->where("modelo", Modelos::NFCE);
    }

    /**
     * Filtra por SAT (modelo 59).
     * Atalho para modelo('59')
     *
     * @return static
     */
    public function modeloSat(): static
    {
        return $this->where("modelo", Modelos::SAT);
    }

    /**
     * Manifesta uma NF-e.
     * Endpoint: /v1-cloud/manifestacao/nfe/manifestar
     *
     * @param array<string, mixed> $data Dados da manifestação
     * @return array<string, mixed> Resposta da API
     */
    public function manifestar(array $data): array
    {
        try {
            $response = $this->http->post(
                "/v1-cloud/manifestacao/nfe/manifestar",
                $data,
            );
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }
}
