<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;
use Jcf\EspiaoNfe\Constants\Modelos;
use Jcf\EspiaoNfe\Exceptions\EspiaoNfeException;
use Jcf\EspiaoNfe\Query\Concerns\HasCnpjCpf;
use Jcf\EspiaoNfe\Query\Concerns\HasPeriodo;

class XmlsQuery extends QueryBuilder
{
    use HasCnpjCpf, HasPeriodo;

    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/consulta/periodo/xmls");
    }

    /**
     * Define o tipo de período.
     * Tipos aceitos: E (período de emissão do XML) ou I (período de inclusão no Espião Cloud)
     *
     * @param string $tipo O tipo de período (E ou I)
     * @return static
     * @throws \InvalidArgumentException Se o tipo for inválido
     */
    public function tipoPeriodo(string $tipo): static
    {
        $tiposValidos = ["E", "I"];

        if (!in_array($tipo, $tiposValidos, true)) {
            throw new \InvalidArgumentException(
                "Tipo de período inválido: '{$tipo}'. Use: E (emissão) ou I (inclusão)",
            );
        }

        return $this->where("tipoPeriodo", $tipo);
    }

    /**
     * Define o tipo de período como Emissão.
     * Consulta XMLs pelo período de emissão do documento.
     *
     * @return static
     */
    public function tipoPeriodoEmissao(): static
    {
        return $this->where("tipoPeriodo", "E");
    }

    /**
     * Define o tipo de período como Inclusão.
     * Consulta XMLs pelo período de inclusão no Espião Cloud.
     *
     * @return static
     */
    public function tipoPeriodoInclusao(): static
    {
        return $this->where("tipoPeriodo", "I");
    }

    /**
     * Define o modelo do documento fiscal.
     * Modelos aceitos: 55 (NF-e), 65 (NFC-e), 57 (CT-e), 67 (CT-e OS), 59 (SAT), 41 (NFS-e Nacional)
     *
     * @param string $modelo O modelo do documento fiscal
     * @return static
     * @throws \InvalidArgumentException Se o modelo for inválido
     */
    public function modelo(string $modelo): static
    {
        Modelos::validar(
            $modelo,
            [
                Modelos::NFE,
                Modelos::NFCE,
                Modelos::CTE,
                Modelos::CTE_OS,
                Modelos::SAT,
                Modelos::NFSE,
            ],
            "XMLs",
        );

        return $this->where("modelo", $modelo);
    }

    /**
     * Filtra por NF-e (modelo 55).
     * Atalho para modelo('55')
     *
     * @return static
     */
    public function modeloNfe(): static
    {
        return $this->where("modelo", Modelos::NFE);
    }

    /**
     * Filtra por NFC-e (modelo 65).
     * Atalho para modelo('65')
     *
     * @return static
     */
    public function modeloNfce(): static
    {
        return $this->where("modelo", Modelos::NFCE);
    }

    /**
     * Filtra por CT-e (modelo 57).
     * Atalho para modelo('57')
     *
     * @return static
     */
    public function modeloCte(): static
    {
        return $this->where("modelo", Modelos::CTE);
    }

    /**
     * Filtra por CT-e OS (modelo 67).
     * Atalho para modelo('67')
     *
     * @return static
     */
    public function modeloCteOs(): static
    {
        return $this->where("modelo", Modelos::CTE_OS);
    }

    /**
     * Filtra por SAT (modelo 59).
     * Atalho para modelo('59')
     *
     * @return static
     */
    public function modeloSat(): static
    {
        return $this->where("modelo", Modelos::SAT);
    }

    /**
     * Filtra por NFS-e Nacional (modelo 41).
     * Atalho para modelo('41')
     *
     * @return static
     */
    public function modeloNfse(): static
    {
        return $this->where("modelo", Modelos::NFSE);
    }

    /**
     * Obtém XML por chave de acesso.
     * Endpoint: /v1-cloud/consulta/chave/xml
     *
     * @param string $chave Chave de acesso do documento
     * @return array<string, mixed> Resposta da API
     */
    public function porChave(string $chave): array
    {
        try {
            $response = $this->http->get("/v1-cloud/consulta/chave/xml", [
                "chave" => $chave,
            ]);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }

    /**
     * Obtém PDF por chave de acesso.
     * Endpoint: /v1-cloud/consulta/chave/pdf
     * Retorna o conteúdo binário do PDF.
     *
     * @param string $chave Chave de acesso do documento
     * @return string Conteúdo binário do PDF
     * @throws EspiaoNfeException Se a requisição falhar
     */
    public function pdfPorChave(string $chave): string
    {
        try {
            $response = $this->http->get("/v1-cloud/consulta/chave/pdf", [
                "chave" => $chave,
            ]);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleHttpException($e);
        }

        if ($response->failed()) {
            $status = $response->status();
            $body = $response->body();
            throw new EspiaoNfeException(
                "Erro ao obter PDF: {$body}. Status: {$status}.",
                $status,
            );
        }

        // Retorna conteúdo binário em vez de JSON
        return $response->body();
    }

    /**
     * Importa um XML.
     * Endpoint: /v1-cloud/importar/xml
     *
     * @param array<string, mixed> $data Dados do XML a importar
     * @return array<string, mixed> Resposta da API
     */
    public function importar(array $data): array
    {
        try {
            $response = $this->http->post("/v1-cloud/importar/xml", $data);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }
}
