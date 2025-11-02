<?php

namespace Jcf\EspiaoNfe\Tests\Unit;

use Jcf\EspiaoNfe\Constants\Modelos;
use Jcf\EspiaoNfe\Tests\TestCase;

class ModelosConstantsTest extends TestCase
{
    /**
     * Test that all modelo constants are defined correctly.
     */
    public function test_modelo_constants_are_defined(): void
    {
        $this->assertEquals('55', Modelos::NFE);
        $this->assertEquals('65', Modelos::NFCE);
        $this->assertEquals('57', Modelos::CTE);
        $this->assertEquals('67', Modelos::CTE_OS);
        $this->assertEquals('59', Modelos::SAT);
        $this->assertEquals('41', Modelos::NFSE);
    }

    /**
     * Test that validacao accepts valid models.
     */
    public function test_validacao_accepts_valid_models(): void
    {
        $this->expectNotToPerformAssertions();

        Modelos::validar(Modelos::NFE, [Modelos::NFE, Modelos::NFCE], 'Test');
        Modelos::validar(Modelos::NFCE, [Modelos::NFE, Modelos::NFCE], 'Test');
    }

    /**
     * Test that validacao rejects invalid models.
     */
    public function test_validacao_rejects_invalid_models(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Modelo inválido");

        Modelos::validar('99', [Modelos::NFE, Modelos::NFCE], 'Test');
    }
}

