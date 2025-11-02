<?php

namespace Jcf\EspiaoNfe\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        protected int $timeout = 30,
        protected int $retry = 3,
        protected int $retryDelay = 100,
        protected bool $logRequests = false,
    ) {
        if (empty(trim($this->espCloudToken)) || empty(trim($this->userToken))) {
            throw new EspiaoNfeException(
                "Os tokens esp_cloud_token e user_token são obrigatórios.",
            );
        }

        $this->http = Http::baseUrl($this->baseUri)
            ->timeout($this->timeout)
            ->retry($this->retry, $this->retryDelay)
            ->withHeaders([
                "esp-cloud-token" => $this->espCloudToken,
                "user-token" => $this->userToken,
                "Accept" => "application/json",
            ]);
    }

    /**
     * Executa uma requisição HTTP.
     *
     * @param string $method Método HTTP (GET, POST, PUT, DELETE)
     * @param string $uri URI do endpoint
     * @param array $data Dados da requisição
     * @return array<string, mixed> Resposta da API
     * @throws EspiaoNfeException
     */
    protected function makeRequest(
        string $method,
        string $uri,
        array $data = [],
    ): array {
        if ($this->logRequests) {
            Log::debug('EspiaoNfe Request', [
                'method' => $method,
                'uri' => $uri,
                'data' => $data,
            ]);
        }

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
            if ($this->logRequests) {
                Log::error('EspiaoNfe Request Failed', [
                    'method' => $method,
                    'uri' => $uri,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }

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
