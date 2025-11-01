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
            'data' => [
                [
                    'serial' => '123456789',
                    'nome' => 'Certificado Teste',
                    'valido' => true,
                ],
            ],
        ];

        Http::fake([
            'api.test.com/*' => Http::response($fakeResponse, 200),
        ]);

        config(['espiaonfe.base_uri' => 'https://api.test.com']);

        $this->app->forgetInstance('espiaonfe');

        $response = EspiaoNfe::certificados()->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://api.test.com/v1-cloud/certificados'
                && $request->method() === 'GET';
        });
    }

    /**
     * Test that the client can use fluent API with pagination.
     */
    public function test_it_can_get_empresas_with_pagination(): void
    {
        $fakeResponse = [
            'data' => [
                ['cnpj' => '12345678000190', 'razao_social' => 'Empresa Teste'],
            ],
            'pagina' => 1,
        ];

        Http::fake([
            'api.test.com/*' => Http::response($fakeResponse, 200),
        ]);

        config(['espiaonfe.base_uri' => 'https://api.test.com']);

        $this->app->forgetInstance('espiaonfe');

        $response = EspiaoNfe::empresas()->pagina(1)->limite(10)->get();

        $this->assertEquals($fakeResponse, $response);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'pagina=1')
                && str_contains($request->url(), 'limite=10')
                && $request->method() === 'GET';
        });
    }
}
