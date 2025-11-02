<?php

namespace Jcf\EspiaoNfe\Query\Concerns;

trait HasCnpjCpf
{
    /**
     * Filtra por CNPJ/CPF da empresa.
     *
     * @param string $cnpjCpf CNPJ ou CPF (com ou sem formatação)
     * @return static
     * @throws \InvalidArgumentException Se o CNPJ/CPF for inválido
     */
    public function cnpjCpf(string $cnpjCpf): static
    {
        $this->validateCnpjCpf($cnpjCpf);
        return $this->where("cnpjCpf", $cnpjCpf);
    }

    /**
     * Valida formato básico de CNPJ/CPF.
     *
     * @param string $cnpjCpf CNPJ ou CPF
     * @return void
     * @throws \InvalidArgumentException Se o CNPJ/CPF for inválido
     */
    protected function validateCnpjCpf(string $cnpjCpf): void
    {
        $cnpjCpfLimpo = preg_replace('/\D/', '', $cnpjCpf);

        if (empty($cnpjCpfLimpo)) {
            throw new \InvalidArgumentException(
                "CNPJ/CPF não pode estar vazio"
            );
        }

        $tamanho = strlen($cnpjCpfLimpo);
        if ($tamanho !== 11 && $tamanho !== 14) {
            throw new \InvalidArgumentException(
                "CNPJ/CPF inválido: deve ter 11 dígitos (CPF) ou 14 dígitos (CNPJ). Fornecido: {$tamanho} dígitos"
            );
        }
    }
}

