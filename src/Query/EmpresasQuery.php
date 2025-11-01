<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class EmpresasQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, '/v1-cloud/empresas');
    }

    /**
     * Filtra por CNPJ/CPF.
     */
    public function cnpjCpf(string $cnpjCpf): static
    {
        return $this->where('cnpj_cpf', $cnpjCpf);
    }

    /**
     * Filtra por razão social.
     */
    public function razaoSocial(string $razaoSocial): static
    {
        return $this->where('razao_social', $razaoSocial);
    }
}
