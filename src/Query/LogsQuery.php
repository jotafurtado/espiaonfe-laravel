<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;
use Jcf\EspiaoNfe\Constants\Modelos;
use Jcf\EspiaoNfe\Query\Concerns\HasCnpjCpf;
use Jcf\EspiaoNfe\Query\Concerns\HasPeriodo;

class LogsQuery extends QueryBuilder
{
    use HasCnpjCpf, HasPeriodo;

    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/consulta/periodo/logs");
    }

    /**
     * Filtra por tipo de log.
     *
     * @param string $tipo Tipo do log
     * @return static
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
        Modelos::validar($modelo, [Modelos::NFE, Modelos::CTE], 'Logs');

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
        return $this->where("modelo", Modelos::NFE);
    }

    /**
     * Filtra logs de CT-e (modelo 57).
     * Atalho para modelo('57')
     *
     * @return static
     */
    public function modeloCte(): static
    {
        return $this->where("modelo", Modelos::CTE);
    }
}
