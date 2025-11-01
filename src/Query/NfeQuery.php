<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class NfeQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, '/v1-cloud/nfe');
    }

    /**
     * Obtém o resumo de NF-e.
     */
    public function resumo(): static
    {
        $this->endpoint = '/v1-cloud/nfe/resumo';

        return $this;
    }

    /**
     * Manifesta uma NF-e.
     */
    public function manifestar(array $data): array
    {
        $this->endpoint = '/v1-cloud/nfe/manifestar';

        return $this->create($data);
    }
}
