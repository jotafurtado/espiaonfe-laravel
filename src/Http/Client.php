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

    // Métodos legados (mantidos para compatibilidade)

    public function getCertificados(): array
    {
        return $this->makeRequest("GET", "/v1-cloud/certificados");
    }

    public function createCertificado(array $data): array
    {
        return $this->makeRequest("POST", "/v1-cloud/certificados", $data);
    }

    public function updateCertificado(string $serial, array $data): array
    {
        return $this->makeRequest(
            "PUT",
            "/v1-cloud/certificados/{$serial}",
            $data,
        );
    }

    public function deleteCertificado(string $serial, string $comando): array
    {
        return $this->makeRequest(
            "DELETE",
            "/v1-cloud/certificados/{$serial}",
            ["comando" => $comando],
        );
    }

    public function getEmpresas(array $params = []): array
    {
        return $this->makeRequest("GET", "/v1-cloud/empresas", $params);
    }

    public function createEmpresa(array $data): array
    {
        return $this->makeRequest("POST", "/v1-cloud/empresas", $data);
    }

    public function getEmpresa(array $params): array
    {
        return $this->makeRequest("GET", "/v1-cloud/empresas", $params);
    }

    public function updateEmpresa(string $cnpjCpf, array $data): array
    {
        return $this->makeRequest(
            "PUT",
            "/v1-cloud/empresas/{$cnpjCpf}",
            $data,
        );
    }

    public function getNfeResumo(array $params): array
    {
        return $this->makeRequest("GET", "/v1-cloud/nfe/resumo", $params);
    }

    public function getCteResumo(array $params): array
    {
        return $this->makeRequest("GET", "/v1-cloud/cte/resumo", $params);
    }

    public function getNfseResumo(array $params): array
    {
        return $this->makeRequest("GET", "/v1-cloud/nfse/resumo", $params);
    }

    public function getXmls(array $params): array
    {
        return $this->makeRequest("GET", "/v1-cloud/xmls", $params);
    }

    public function getLogs(array $params): array
    {
        return $this->makeRequest("GET", "/v1-cloud/logs", $params);
    }

    public function getXmlByChave(array $params): array
    {
        return $this->makeRequest("GET", "/v1-cloud/xmls/chave", $params);
    }

    public function getPdfByChave(array $params): array
    {
        return $this->makeRequest("GET", "/v1-cloud/pdfs/chave", $params);
    }

    public function importarXml(array $data): array
    {
        return $this->makeRequest("POST", "/v1-cloud/xmls/importar", $data);
    }

    public function manifestarNfe(array $data): array
    {
        return $this->makeRequest("POST", "/v1-cloud/nfe/manifestar", $data);
    }

    public function desacordoCte(array $data): array
    {
        return $this->makeRequest("POST", "/v1-cloud/cte/desacordo", $data);
    }

    public function consultarNfsePorCidade(array $params): array
    {
        return $this->makeRequest(
            "GET",
            "/v1-cloud/nfse/consultar-cidade",
            $params,
        );
    }

    public function getCidadesHomologadas(): array
    {
        return $this->makeRequest("GET", "/v1-cloud/cidades-homologadas");
    }

    public function inserirChavesResgateXml(array $data): array
    {
        return $this->makeRequest(
            "POST",
            "/v1-cloud/xmls/resgate/chaves",
            $data,
        );
    }

    public function consultarAndamentoResgateXml(array $params): array
    {
        return $this->makeRequest(
            "GET",
            "/v1-cloud/xmls/resgate/andamento",
            $params,
        );
    }

    public function consultarResgatados(array $params): array
    {
        return $this->makeRequest(
            "GET",
            "/v1-cloud/xmls/resgate/resgatados",
            $params,
        );
    }
}
