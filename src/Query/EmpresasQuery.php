<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;
use Jcf\EspiaoNfe\Query\Concerns\HasCnpjCpf;

class EmpresasQuery extends QueryBuilder
{
    use HasCnpjCpf;

    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, '/v1-cloud/empresas');
    }

    /**
     * Filtra por razão social.
     *
     * @param string $razaoSocial Razão social da empresa
     * @return static
     */
    public function razaoSocial(string $razaoSocial): static
    {
        return $this->where('razao_social', $razaoSocial);
    }
}
