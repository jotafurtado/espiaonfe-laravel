<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;
use Jcf\EspiaoNfe\Constants\Modelos;
use Jcf\EspiaoNfe\Query\Concerns\HasCnpjCpf;
use Jcf\EspiaoNfe\Query\Concerns\HasPeriodo;

class CteQuery extends QueryBuilder
{
    use HasCnpjCpf, HasPeriodo;

    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/consulta/periodo/cte-resumo");
    }

    /**
     * Define o modelo do CT-e.
     * Modelo aceito: 57 (CT-e)
     *
     * @param string $modelo O modelo do documento fiscal
     * @return static
     * @throws \InvalidArgumentException Se o modelo for inválido
     */
    public function modelo(string $modelo): static
    {
        Modelos::validar($modelo, [Modelos::CTE], 'CT-e');

        return $this->where("modelo", $modelo);
    }

    /**
     * Filtra por CT-e (modelo 57).
     * Atalho para modelo('57')
     *
     * @return static
     */
    public function modeloCte(): static
    {
        return $this->where("modelo", Modelos::CTE);
    }

    /**
     * Registra desacordo de CT-e.
     * Endpoint: /v1-cloud/manifestacao/cte/desacordo
     *
     * @param array<string, mixed> $data Dados do desacordo
     * @return array<string, mixed> Resposta da API
     */
    public function desacordo(array $data): array
    {
        try {
            $response = $this->http->post(
                "/v1-cloud/manifestacao/cte/desacordo",
                $data,
            );
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }
}
