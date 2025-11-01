<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class ResgateXmlQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, '/v1-cloud/xmls/resgate');
    }

    /**
     * Insere chaves para resgate de XML.
     */
    public function inserirChaves(array $data): array
    {
        $this->endpoint = '/v1-cloud/xmls/resgate/chaves';

        return $this->create($data);
    }

    /**
     * Consulta andamento do resgate de XML.
     */
    public function andamento(array $params = []): array
    {
        $this->endpoint = '/v1-cloud/xmls/resgate/andamento';

        if (! empty($params)) {
            $this->setParams($params);
        }

        return $this->get();
    }

    /**
     * Consulta XMLs resgatados.
     */
    public function resgatados(array $params = []): array
    {
        $this->endpoint = '/v1-cloud/xmls/resgate/resgatados';

        if (! empty($params)) {
            $this->setParams($params);
        }

        return $this->get();
    }
}
