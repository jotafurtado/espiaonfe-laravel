<?php

namespace Jcf\EspiaoNfe\Query\Concerns;

trait HasPeriodo
{
    /**
     * Define a data inicial (formato: DD/MM/AAAA).
     *
     * @param string $dataInicial Data no formato DD/MM/AAAA
     * @return static
     * @throws \InvalidArgumentException Se o formato ou a data for inválida
     */
    public function dataInicial(string $dataInicial): static
    {
        $this->validateDateFormat($dataInicial);
        return $this->where("dataInicial", $dataInicial);
    }

    /**
     * Define a data final (formato: DD/MM/AAAA).
     *
     * @param string $dataFinal Data no formato DD/MM/AAAA
     * @return static
     * @throws \InvalidArgumentException Se o formato ou a data for inválida
     */
    public function dataFinal(string $dataFinal): static
    {
        $this->validateDateFormat($dataFinal);
        return $this->where("dataFinal", $dataFinal);
    }

    /**
     * Define o período de consulta.
     *
     * @param string $dataInicial Data inicial no formato DD/MM/AAAA
     * @param string $dataFinal Data final no formato DD/MM/AAAA
     * @return static
     * @throws \InvalidArgumentException Se o formato, a data ou o período for inválido
     */
    public function periodo(string $dataInicial, string $dataFinal): static
    {
        $this->validateDateFormat($dataInicial);
        $this->validateDateFormat($dataFinal);

        // Validar se a data inicial é anterior à data final
        $inicial = \DateTime::createFromFormat('d/m/Y', $dataInicial);
        $final = \DateTime::createFromFormat('d/m/Y', $dataFinal);

        if ($inicial === false || $final === false) {
            throw new \InvalidArgumentException(
                "Erro ao processar datas. Verifique os formatos."
            );
        }

        if ($inicial > $final) {
            throw new \InvalidArgumentException(
                "A data inicial ({$dataInicial}) deve ser anterior ou igual à data final ({$dataFinal})"
            );
        }

        return $this->dataInicial($dataInicial)->dataFinal($dataFinal);
    }

    /**
     * Valida o formato de data DD/MM/AAAA.
     *
     * @param string $date Data a ser validada
     * @return void
     * @throws \InvalidArgumentException Se o formato ou a data for inválida
     */
    protected function validateDateFormat(string $date): void
    {
        if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
            throw new \InvalidArgumentException(
                "Formato de data inválido: '{$date}'. Use: DD/MM/AAAA"
            );
        }

        $parts = explode('/', $date);
        if (!checkdate((int)$parts[1], (int)$parts[0], (int)$parts[2])) {
            throw new \InvalidArgumentException(
                "Data inválida: '{$date}'"
            );
        }
    }
}

