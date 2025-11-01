<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class NfseQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, '/v1-cloud/nfse');
    }

    /**
     * Obtém o resumo de NFSe.
     */
    public function resumo(): static
    {
        $this->endpoint = '/v1-cloud/nfse/resumo';

        return $this;
    }

    /**
     * Consulta NFSe por cidade.
     */
    public function porCidade(array $params = []): array
    {
        $this->endpoint = '/v1-cloud/nfse/consultar-cidade';

        if (! empty($params)) {
            $this->setParams($params);
        }

        return $this->get();
    }

    /**
     * Lista cidades homologadas.
     */
    public function cidadesHomologadas(): array
    {
        $this->endpoint = '/v1-cloud/cidades-homologadas';

        return $this->get();
    }
}
