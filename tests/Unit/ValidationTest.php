<?php

namespace Jcf\EspiaoNfe\Tests\Unit;

use Jcf\EspiaoNfe\Facades\EspiaoNfe;
use Jcf\EspiaoNfe\Tests\TestCase;

class ValidationTest extends TestCase
{
    /**
     * Test that date format validation works correctly.
     */
    public function test_it_validates_date_format(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Formato de data inválido");

        EspiaoNfe::nfe()
            ->cnpjCpf('12345678000190')
            ->dataInicial('2024-01-01') // Formato inválido
            ->dataFinal('31/01/2024');
    }

    /**
     * Test that invalid dates are rejected.
     */
    public function test_it_validates_invalid_dates(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Data inválida");

        EspiaoNfe::nfe()
            ->cnpjCpf('12345678000190')
            ->dataInicial('32/01/2024') // Data inválida
            ->dataFinal('31/01/2024');
    }

    /**
     * Test that period validation works (initial date must be before final date).
     */
    public function test_it_validates_period_order(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("A data inicial");

        EspiaoNfe::nfe()
            ->cnpjCpf('12345678000190')
            ->periodo('31/01/2024', '01/01/2024'); // Data inicial depois da final
    }

    /**
     * Test that CNPJ/CPF validation works.
     */
    public function test_it_validates_cnpj_cpf_format(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("CNPJ/CPF inválido");

        EspiaoNfe::nfe()
            ->cnpjCpf('123'); // CNPJ/CPF muito curto
    }

    /**
     * Test that empty CNPJ/CPF is rejected.
     */
    public function test_it_validates_empty_cnpj_cpf(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("CNPJ/CPF não pode estar vazio");

        EspiaoNfe::nfe()
            ->cnpjCpf(''); // CNPJ/CPF vazio
    }

    /**
     * Test that modelo validation works in NfeQuery.
     */
    public function test_it_validates_nfe_modelo(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Modelo inválido");

        EspiaoNfe::nfe()
            ->cnpjCpf('12345678000190')
            ->periodo('01/01/2024', '31/01/2024')
            ->modelo('99'); // Modelo inválido para NF-e
    }

    /**
     * Test that modelo validation works in CteQuery.
     */
    public function test_it_validates_cte_modelo(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Modelo inválido");

        EspiaoNfe::cte()
            ->cnpjCpf('12345678000190')
            ->periodo('01/01/2024', '31/01/2024')
            ->modelo('55'); // Modelo inválido para CT-e (deve ser 57)
    }

    /**
     * Test that modelo validation works in LogsQuery.
     */
    public function test_it_validates_logs_modelo(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Modelo inválido");

        EspiaoNfe::logs()
            ->cnpjCpf('12345678000190')
            ->periodo('01/01/2024', '31/01/2024')
            ->modelo('99'); // Modelo inválido para Logs
    }

    /**
     * Test that tipoPeriodo validation works.
     */
    public function test_it_validates_tipo_periodo(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Tipo de período inválido");

        EspiaoNfe::xmls()
            ->cnpjCpf('12345678000190')
            ->periodo('01/01/2024', '31/01/2024')
            ->tipoPeriodo('X'); // Tipo inválido
    }

    /**
     * Test that valid CNPJ works.
     */
    public function test_it_accepts_valid_cnpj(): void
    {
        $this->expectNotToPerformAssertions();

        // Não deve lançar exceção
        EspiaoNfe::nfe()
            ->cnpjCpf('12345678000190') // CNPJ válido (14 dígitos)
            ->periodo('01/01/2024', '31/01/2024');
    }

    /**
     * Test that valid CPF works.
     */
    public function test_it_accepts_valid_cpf(): void
    {
        $this->expectNotToPerformAssertions();

        // Não deve lançar exceção
        EspiaoNfe::nfe()
            ->cnpjCpf('12345678901') // CPF válido (11 dígitos)
            ->periodo('01/01/2024', '31/01/2024');
    }

    /**
     * Test that formatted CNPJ/CPF works (formatação é removida).
     */
    public function test_it_accepts_formatted_cnpj_cpf(): void
    {
        $this->expectNotToPerformAssertions();

        // Não deve lançar exceção - formatação é removida
        EspiaoNfe::nfe()
            ->cnpjCpf('12.345.678/0001-90') // CNPJ formatado
            ->periodo('01/01/2024', '31/01/2024');
    }

    /**
     * Test that valid dates work.
     */
    public function test_it_accepts_valid_dates(): void
    {
        $this->expectNotToPerformAssertions();

        // Não deve lançar exceção
        EspiaoNfe::nfe()
            ->cnpjCpf('12345678000190')
            ->periodo('01/01/2024', '31/01/2024');
    }

    /**
     * Test that valid modelo works.
     */
    public function test_it_accepts_valid_modelo(): void
    {
        $this->expectNotToPerformAssertions();

        // Não deve lançar exceção
        EspiaoNfe::nfe()
            ->cnpjCpf('12345678000190')
            ->periodo('01/01/2024', '31/01/2024')
            ->modelo('55'); // Modelo válido
    }
}

