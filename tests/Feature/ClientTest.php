<?php

namespace Jcf\EspiaoNfe\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Jcf\EspiaoNfe\Facades\EspiaoNfe;
use Jcf\EspiaoNfe\Tests\TestCase;

class ClientTest extends TestCase
{
    /**
     * Test that the client can get certificates.
     */
    public function test_it_can_get_certificados(): void
    {
        $fakeResponse = [
            "data" => [
                [
                    "serial" => "123456789",
                    "nome" => "Certificado Teste",
                    "valido" => true,
                ],
            ],
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::certificados()->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return $request->url() ===
                "https://api.test.com/v1-cloud/certificados" &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that the client can use fluent API with pagination.
     */
    public function test_it_can_get_empresas_with_pagination(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "cnpjCpf" => "12345678000190",
                    "razaoSocial" => "Empresa Teste",
                ],
            ],
            "codigoProximaPagina" => "200",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::empresas()->codigoProximaPagina("200")->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "codigoProximaPagina=200") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that XMLs query can use tipoPeriodoEmissao() method.
     */
    public function test_it_can_use_tipo_periodo_emissao(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "chave" => "35191234567890123456789012345678901234567890",
                    "modelo" => "55",
                ],
            ],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::xmls()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->tipoPeriodoEmissao()
            ->modelo("55")
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "tipoPeriodo=E") &&
                str_contains($request->url(), "modelo=55") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that XMLs query can use tipoPeriodoInclusao() method.
     */
    public function test_it_can_use_tipo_periodo_inclusao(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "chave" => "35191234567890123456789012345678901234567890",
                    "modelo" => "57",
                ],
            ],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::xmls()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->tipoPeriodoInclusao()
            ->modelo("57")
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "tipoPeriodo=I") &&
                str_contains($request->url(), "modelo=57") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that XMLs query still accepts tipoPeriodo() directly.
     */
    public function test_it_can_use_tipo_periodo_directly(): void
    {
        $fakeResponse = [
            "dados" => [],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::xmls()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->tipoPeriodo("E")
            ->modelo("55")
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "tipoPeriodo=E") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that Logs query can use modeloNfe() method.
     */
    public function test_it_can_use_modelo_nfe_in_logs(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "tipo" => "consulta",
                    "modelo" => "55",
                ],
            ],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::logs()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->modeloNfe()
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "modelo=55") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that Logs query can use modeloCte() method.
     */
    public function test_it_can_use_modelo_cte_in_logs(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "tipo" => "consulta",
                    "modelo" => "57",
                ],
            ],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::logs()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->modeloCte()
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "modelo=57") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that Logs query validates modelo parameter.
     */
    public function test_it_validates_modelo_in_logs(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            "Modelo inválido: '99'. Use: 55 (NF-e) ou 57 (CT-e)",
        );

        EspiaoNfe::logs()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->modelo("99");
    }

    /**
     * Test that NfeQuery can use modeloNfe() method.
     */
    public function test_it_can_use_modelo_nfe_in_nfe_query(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "numero" => "123",
                    "modelo" => "55",
                ],
            ],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::nfe()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->modeloNfe()
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "modelo=55") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that NfeQuery can use modeloNfce() method.
     */
    public function test_it_can_use_modelo_nfce_in_nfe_query(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "numero" => "456",
                    "modelo" => "65",
                ],
            ],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::nfe()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->modeloNfce()
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "modelo=65") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that NfeQuery can use modeloSat() method.
     */
    public function test_it_can_use_modelo_sat_in_nfe_query(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "numero" => "789",
                    "modelo" => "59",
                ],
            ],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::nfe()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->modeloSat()
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "modelo=59") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that CteQuery can use modeloCte() method.
     */
    public function test_it_can_use_modelo_cte_in_cte_query(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "numero" => "123",
                    "modelo" => "57",
                ],
            ],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::cte()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->modeloCte()
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "modelo=57") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that XmlsQuery can use modeloNfe() method.
     */
    public function test_it_can_use_modelo_nfe_in_xmls_query(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "xml" => "base64data",
                    "situacao" => "Autorizada",
                ],
            ],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::xmls()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->tipoPeriodoEmissao()
            ->modeloNfe()
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "modelo=55") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that XmlsQuery can use modeloCteOs() method.
     */
    public function test_it_can_use_modelo_cte_os_in_xmls_query(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "xml" => "base64data",
                    "situacao" => "Autorizada",
                ],
            ],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::xmls()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->tipoPeriodoEmissao()
            ->modeloCteOs()
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "modelo=67") &&
                $request->method() === "GET";
        });
    }

    /**
     * Test that XmlsQuery can use modeloNfse() method.
     */
    public function test_it_can_use_modelo_nfse_in_xmls_query(): void
    {
        $fakeResponse = [
            "dados" => [
                [
                    "xml" => "base64data",
                    "situacao" => "Autorizada",
                ],
            ],
            "codigoProximaPagina" => "-1",
        ];

        Http::fake([
            "api.test.com/*" => Http::response($fakeResponse, 200),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);

        $this->app->forgetInstance("espiaonfe");

        $response = EspiaoNfe::xmls()
            ->cnpjCpf("12345678000190")
            ->dataInicial("01/01/2024")
            ->dataFinal("31/01/2024")
            ->tipoPeriodoInclusao()
            ->modeloNfse()
            ->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), "modelo=41") &&
                $request->method() === "GET";
        });
    }
}
