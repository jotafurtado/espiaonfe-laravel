<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class CteQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, '/v1-cloud/cte');
    }

    /**
     * Obtém o resumo de CT-e.
     */
    public function resumo(): static
    {
        $this->endpoint = '/v1-cloud/cte/resumo';

        return $this;
    }

    /**
     * Registra desacordo de CT-e.
     */
    public function desacordo(array $data): array
    {
        $this->endpoint = '/v1-cloud/cte/desacordo';

        return $this->create($data);
    }
}
