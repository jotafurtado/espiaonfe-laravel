<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class XmlsQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, '/v1-cloud/xmls');
    }

    /**
     * Obtém XML por chave de acesso.
     */
    public function porChave(string $chave): array
    {
        $this->endpoint = '/v1-cloud/xmls/chave';

        return $this->where('chave', $chave)->get();
    }

    /**
     * Importa um XML.
     */
    public function importar(array $data): array
    {
        $this->endpoint = '/v1-cloud/xmls/importar';

        return $this->create($data);
    }

    /**
     * Obtém PDF por chave de acesso.
     */
    public function pdfPorChave(string $chave): array
    {
        $this->endpoint = '/v1-cloud/pdfs/chave';

        return $this->where('chave', $chave)->get();
    }
}
