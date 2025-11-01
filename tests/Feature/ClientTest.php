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
}
