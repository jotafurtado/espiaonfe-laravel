<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;

class XmlsQuery extends QueryBuilder
{
    public function __construct(PendingRequest $http)
    {
        parent::__construct($http, "/v1-cloud/consulta/periodo/xmls");
    }

    /**
     * Filtra por CNPJ/CPF da empresa.
     */
    public function cnpjCpf(string $cnpjCpf): static
    {
        return $this->where("cnpjCpf", $cnpjCpf);
    }

    /**
     * Define a data inicial (formato: DD/MM/AAAA).
     */
    public function dataInicial(string $dataInicial): static
    {
        return $this->where("dataInicial", $dataInicial);
    }

    /**
     * Define a data final (formato: DD/MM/AAAA).
     */
    public function dataFinal(string $dataFinal): static
    {
        return $this->where("dataFinal", $dataFinal);
    }

    /**
     * Define o período de consulta.
     */
    public function periodo(string $dataInicial, string $dataFinal): static
    {
        return $this->dataInicial($dataInicial)->dataFinal($dataFinal);
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
        $modelosValidos = ["55", "65", "57", "67", "59", "41"];

        if (!in_array($modelo, $modelosValidos, true)) {
            throw new \InvalidArgumentException(
                "Modelo inválido: '{$modelo}'. Use: 55 (NF-e), 65 (NFC-e), 57 (CT-e), 67 (CT-e OS), 59 (SAT) ou 41 (NFS-e Nacional)",
            );
        }

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
        return $this->where("modelo", "55");
    }

    /**
     * Filtra por NFC-e (modelo 65).
     * Atalho para modelo('65')
     *
     * @return static
     */
    public function modeloNfce(): static
    {
        return $this->where("modelo", "65");
    }

    /**
     * Filtra por CT-e (modelo 57).
     * Atalho para modelo('57')
     *
     * @return static
     */
    public function modeloCte(): static
    {
        return $this->where("modelo", "57");
    }

    /**
     * Filtra por CT-e OS (modelo 67).
     * Atalho para modelo('67')
     *
     * @return static
     */
    public function modeloCteOs(): static
    {
        return $this->where("modelo", "67");
    }

    /**
     * Filtra por SAT (modelo 59).
     * Atalho para modelo('59')
     *
     * @return static
     */
    public function modeloSat(): static
    {
        return $this->where("modelo", "59");
    }

    /**
     * Filtra por NFS-e Nacional (modelo 41).
     * Atalho para modelo('41')
     *
     * @return static
     */
    public function modeloNfse(): static
    {
        return $this->where("modelo", "41");
    }

    /**
     * Obtém XML por chave de acesso.
     * Endpoint: /v1-cloud/consulta/chave/xml
     */
    public function porChave(string $chave): array
    {
        $response = $this->http->get("/v1-cloud/consulta/chave/xml", [
            "chave" => $chave,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Obtém PDF por chave de acesso.
     * Endpoint: /v1-cloud/consulta/chave/pdf
     */
    public function pdfPorChave(string $chave): array
    {
        $response = $this->http->get("/v1-cloud/consulta/chave/pdf", [
            "chave" => $chave,
        ]);

        return $this->handleResponse($response);
    }

    /**
     * Importa um XML.
     * Endpoint: /v1-cloud/importar/xml
     */
    public function importar(array $data): array
    {
        $response = $this->http->post("/v1-cloud/importar/xml", $data);

        return $this->handleResponse($response);
    }
}
