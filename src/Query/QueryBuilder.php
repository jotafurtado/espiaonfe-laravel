<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;
use Jcf\EspiaoNfe\Exceptions\EspiaoNfeException;

abstract class QueryBuilder
{
    protected array $params = [];

    protected array $data = [];

    protected ?string $resourceId = null;

    public function __construct(
        protected PendingRequest $http,
        protected string $endpoint
    ) {}

    /**
     * Adiciona um parâmetro de query string.
     */
    public function where(string $key, mixed $value): static
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * Define o ID do recurso específico.
     */
    public function find(string $id): static
    {
        $this->resourceId = $id;

        return $this;
    }

    /**
     * Define o número da página para paginação.
     */
    public function pagina(int|string $pagina): static
    {
        return $this->where('pagina', $pagina);
    }

    /**
     * Define o limite de registros por página.
     */
    public function limite(int $limite): static
    {
        return $this->where('limite', $limite);
    }

    /**
     * Define múltiplos parâmetros de uma vez.
     */
    public function setParams(array $params): static
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    /**
     * Define os dados para requisições POST/PUT.
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Executa uma requisição GET.
     */
    public function get(): array
    {
        $uri = $this->buildUri();

        $response = $this->http->get($uri, $this->params);

        return $this->handleResponse($response);
    }

    /**
     * Executa uma requisição POST.
     */
    public function create(array $data = []): array
    {
        $uri = $this->buildUri();
        $payload = ! empty($data) ? $data : $this->data;

        $response = $this->http->post($uri, $payload);

        return $this->handleResponse($response);
    }

    /**
     * Executa uma requisição PUT.
     */
    public function update(array $data = []): array
    {
        if (! $this->resourceId) {
            throw new EspiaoNfeException('É necessário informar o ID do recurso para atualização.');
        }

        $uri = $this->buildUri();
        $payload = ! empty($data) ? $data : $this->data;

        $response = $this->http->put($uri, $payload);

        return $this->handleResponse($response);
    }

    /**
     * Executa uma requisição DELETE.
     */
    public function delete(array $data = []): array
    {
        if (! $this->resourceId) {
            throw new EspiaoNfeException('É necessário informar o ID do recurso para exclusão.');
        }

        $uri = $this->buildUri();
        $payload = ! empty($data) ? $data : $this->data;

        $response = $this->http->delete($uri, $payload);

        return $this->handleResponse($response);
    }

    /**
     * Constrói a URI da requisição.
     */
    protected function buildUri(): string
    {
        $uri = $this->endpoint;

        if ($this->resourceId) {
            $uri .= '/'.$this->resourceId;
        }

        return $uri;
    }

    /**
     * Trata a resposta da requisição.
     *
     * @throws EspiaoNfeException
     */
    protected function handleResponse($response): array
    {
        if ($response->failed()) {
            throw new EspiaoNfeException(
                "Erro na requisição: {$response->body()}. Status: {$response->status()}."
            );
        }

        return $response->json() ?? [];
    }
}
