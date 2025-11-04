<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class CertificadosQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/certificados");
    }

    /**
     * Filtra por serial do certificado.
     *
     * @param string $serial Serial do certificado
     * @return static
     */
    public function serial(string $serial): static
    {
        return $this->where("serial", $serial);
    }
}
