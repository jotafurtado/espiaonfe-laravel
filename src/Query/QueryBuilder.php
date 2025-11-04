<?php

namespace Jcf\EspiaoNfe\Query;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Log;
use Jcf\EspiaoNfe\Exceptions\AuthenticationException;
use Jcf\EspiaoNfe\Exceptions\EspiaoNfeException;
use Jcf\EspiaoNfe\Exceptions\NotFoundException;
use Jcf\EspiaoNfe\Exceptions\ValidationException;

abstract class QueryBuilder
{
    protected array $params = [];

    protected array $data = [];

    protected ?string $resourceId = null;

    protected bool $logRequests = false;

    public function __construct(
        protected PendingRequest $http,
        protected string $endpoint,
    ) {}

    /**
     * Adiciona um parâmetro de query string.
     *
     * @param string $key Chave do parâmetro
     * @param mixed $value Valor do parâmetro
     * @return static
     */
    public function where(string $key, mixed $value): static
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * Define o ID do recurso específico.
     *
     * @param string $id ID do recurso
     * @return static
     */
    public function find(string $id): static
    {
        $this->resourceId = $id;

        return $this;
    }

    /**
     * Define o código da próxima página para paginação.
     * A API retorna este código no campo "codigoProximaPagina".
     *
     * @param string $codigo Código da próxima página
     * @return static
     */
    public function codigoProximaPagina(string $codigo): static
    {
        return $this->where("codigoProximaPagina", $codigo);
    }

    /**
     * Define múltiplos parâmetros de uma vez.
     *
     * @param array<string, mixed> $params Parâmetros a serem definidos
     * @return static
     */
    public function setParams(array $params): static
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    /**
     * Define os dados para requisições POST/PUT.
     *
     * @param array<string, mixed> $data Dados a serem definidos
     * @return static
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Executa uma requisição GET.
     *
     * @return array<string, mixed> Resposta da API
     * @throws EspiaoNfeException
     */
    public function get(): array
    {
        $uri = $this->buildUri();

        try {
            $response = $this->http->get($uri, $this->params);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }

    /**
     * Executa uma requisição POST.
     *
     * @param array<string, mixed> $data Dados para a requisição
     * @return array<string, mixed> Resposta da API
     * @throws EspiaoNfeException
     */
    public function create(array $data = []): array
    {
        $uri = $this->buildUri();
        $payload = !empty($data) ? $data : $this->data;

        try {
            $response = $this->http->post($uri, $payload);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }

    /**
     * Executa uma requisição PUT.
     *
     * @param array<string, mixed> $data Dados para a requisição
     * @return array<string, mixed> Resposta da API
     * @throws EspiaoNfeException
     */
    public function update(array $data = []): array
    {
        if (!$this->resourceId) {
            throw new EspiaoNfeException(
                "É necessário informar o ID do recurso para atualização.",
            );
        }

        $uri = $this->buildUri();
        $payload = !empty($data) ? $data : $this->data;

        try {
            $response = $this->http->put($uri, $payload);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }

    /**
     * Executa uma requisição DELETE.
     *
     * @param array<string, mixed> $data Dados para a requisição
     * @return array<string, mixed> Resposta da API
     * @throws EspiaoNfeException
     */
    public function delete(array $data = []): array
    {
        if (!$this->resourceId) {
            throw new EspiaoNfeException(
                "É necessário informar o ID do recurso para exclusão.",
            );
        }

        $uri = $this->buildUri();
        $payload = !empty($data) ? $data : $this->data;

        try {
            $response = $this->http->delete($uri, $payload);
        } catch (\Illuminate\Http\Client\RequestException $e) {
            return $this->handleHttpException($e);
        }

        return $this->handleResponse($response);
    }

    /**
     * Constrói a URI da requisição.
     *
     * @return string URI completa
     */
    protected function buildUri(): string
    {
        $uri = $this->endpoint;

        if ($this->resourceId) {
            $uri .= "/" . $this->resourceId;
        }

        return $uri;
    }

    /**
     * Trata exceções HTTP do Laravel e converte para exceções específicas.
     *
     * @param \Illuminate\Http\Client\RequestException $e Exceção HTTP do Laravel
     * @return never
     * @throws AuthenticationException|NotFoundException|ValidationException|EspiaoNfeException
     */
    protected function handleHttpException(
        \Illuminate\Http\Client\RequestException $e,
    ): never {
        $response = $e->response;
        $status = $response ? $response->status() : 0;
        $body = $response ? $response->body() : $e->getMessage();
        $uri = $this->buildUri();

        if ($this->logRequests) {
            Log::error("EspiaoNfe Query Request Failed", [
                "endpoint" => $uri,
                "status" => $status,
                "body" => $body,
                "exception" => $e->getMessage(),
            ]);
        }

        match ($status) {
            401, 403 => throw new AuthenticationException(
                "Erro de autenticação na API EspiaoNfe. Status: {$status}. Resposta: {$body}",
                $status,
            ),
            404 => throw new NotFoundException(
                "Recurso não encontrado na API EspiaoNfe. Status: {$status}. Resposta: {$body}",
                $status,
            ),
            422 => throw new ValidationException(
                "Erro de validação na API EspiaoNfe. Status: {$status}. Resposta: {$body}",
                $status,
            ),
            default => throw new EspiaoNfeException(
                "Erro na requisição: {$body}. Status: {$status}.",
                $status,
            ),
        };
    }

    /**
     * Trata a resposta da requisição.
     *
     * @param \Illuminate\Http\Client\Response $response Resposta HTTP
     * @return array<string, mixed> Dados da resposta
     * @throws EspiaoNfeException|AuthenticationException|NotFoundException|ValidationException
     */
    protected function handleResponse($response): array
    {
        if ($response->failed()) {
            $status = $response->status();
            $body = $response->body();
            $uri = $this->buildUri();

            if ($this->logRequests) {
                Log::error("EspiaoNfe Query Request Failed", [
                    "endpoint" => $uri,
                    "status" => $status,
                    "body" => $body,
                ]);
            }

            match ($status) {
                401, 403 => throw new AuthenticationException(
                    "Erro de autenticação na API EspiaoNfe. Status: {$status}. Resposta: {$body}",
                    $status,
                ),
                404 => throw new NotFoundException(
                    "Recurso não encontrado na API EspiaoNfe. Status: {$status}. Resposta: {$body}",
                    $status,
                ),
                422 => throw new ValidationException(
                    "Erro de validação na API EspiaoNfe. Status: {$status}. Resposta: {$body}",
                    $status,
                ),
                default => throw new EspiaoNfeException(
                    "Erro na requisição: {$body}. Status: {$status}.",
                    $status,
                ),
            };
        }

        return $response->json() ?? [];
    }
}
