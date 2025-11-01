<?php

namespace Jcf\EspiaoNfe\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Jcf\EspiaoNfe\Exceptions\EspiaoNfeException;
use Jcf\EspiaoNfe\Query\CertificadosQuery;
use Jcf\EspiaoNfe\Query\CteQuery;
use Jcf\EspiaoNfe\Query\EmpresasQuery;
use Jcf\EspiaoNfe\Query\LogsQuery;
use Jcf\EspiaoNfe\Query\NfeQuery;
use Jcf\EspiaoNfe\Query\NfseQuery;
use Jcf\EspiaoNfe\Query\ResgateXmlQuery;
use Jcf\EspiaoNfe\Query\XmlsQuery;

class Client
{
    protected PendingRequest $http;

    public function __construct(
        protected string $espCloudToken,
        protected string $userToken,
        protected string $baseUri,
    ) {
        if (empty($this->espCloudToken) || empty($this->userToken)) {
            throw new EspiaoNfeException(
                "Os tokens esp_cloud_token e user_token são obrigatórios.",
            );
        }

        $this->http = Http::baseUrl($this->baseUri)->withHeaders([
            "esp-cloud-token" => $this->espCloudToken,
            "user-token" => $this->userToken,
            "Accept" => "application/json",
        ]);
    }

    /**
     * Executa uma requisição HTTP.
     *
     * @throws EspiaoNfeException
     */
    protected function makeRequest(
        string $method,
        string $uri,
        array $data = [],
    ): array {
        $response = match (strtoupper($method)) {
            "GET" => $this->http->get($uri, $data),
            "POST" => $this->http->post($uri, $data),
            "PUT" => $this->http->put($uri, $data),
            "DELETE" => $this->http->delete($uri, $data),
            default => throw new EspiaoNfeException(
                "Método HTTP '{$method}' não suportado.",
            ),
        };

        if ($response->failed()) {
            throw new EspiaoNfeException(
                "Erro na requisição: {$response->body()}. Status: {$response->status()}.",
            );
        }

        return $response->json() ?? [];
    }

    /**
     * Retorna um query builder para empresas.
     */
    public function empresas(): EmpresasQuery
    {
        return new EmpresasQuery($this->http);
    }

    /**
     * Retorna um query builder para certificados.
     */
    public function certificados(): CertificadosQuery
    {
        return new CertificadosQuery($this->http);
    }

    /**
     * Retorna um query builder para NF-e.
     */
    public function nfe(): NfeQuery
    {
        return new NfeQuery($this->http);
    }

    /**
     * Retorna um query builder para CT-e.
     */
    public function cte(): CteQuery
    {
        return new CteQuery($this->http);
    }

    /**
     * Retorna um query builder para NFSe.
     */
    public function nfse(): NfseQuery
    {
        return new NfseQuery($this->http);
    }

    /**
     * Retorna um query builder para XMLs.
     */
    public function xmls(): XmlsQuery
    {
        return new XmlsQuery($this->http);
    }

    /**
     * Retorna um query builder para logs.
     */
    public function logs(): LogsQuery
    {
        return new LogsQuery($this->http);
    }

    /**
     * Retorna um query builder para resgate de XML.
     */
    public function resgateXml(): ResgateXmlQuery
    {
        return new ResgateXmlQuery($this->http);
    }
}
