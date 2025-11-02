<?php

namespace Jcf\EspiaoNfe\Tests\Feature;

use Illuminate\Support\Facades\Http;
use Jcf\EspiaoNfe\Exceptions\AuthenticationException;
use Jcf\EspiaoNfe\Exceptions\NotFoundException;
use Jcf\EspiaoNfe\Exceptions\ValidationException;
use Jcf\EspiaoNfe\Facades\EspiaoNfe;
use Jcf\EspiaoNfe\Tests\TestCase;

class ErrorHandlingTest extends TestCase
{
    /**
     * Test that 401 errors throw AuthenticationException.
     */
    public function test_it_throws_authentication_exception_on_401(): void
    {
        Http::fake([
            "api.test.com/*" => Http::response(['error' => 'Unauthorized'], 401),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);
        $this->app->forgetInstance("espiaonfe");

        $this->expectException(AuthenticationException::class);

        EspiaoNfe::certificados()->get();
    }

    /**
     * Test that 403 errors throw AuthenticationException.
     */
    public function test_it_throws_authentication_exception_on_403(): void
    {
        Http::fake([
            "api.test.com/*" => Http::response(['error' => 'Forbidden'], 403),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);
        $this->app->forgetInstance("espiaonfe");

        $this->expectException(AuthenticationException::class);

        EspiaoNfe::certificados()->get();
    }

    /**
     * Test that 404 errors throw NotFoundException.
     */
    public function test_it_throws_not_found_exception_on_404(): void
    {
        Http::fake([
            "api.test.com/*" => Http::response(['error' => 'Not Found'], 404),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);
        $this->app->forgetInstance("espiaonfe");

        $this->expectException(NotFoundException::class);

        EspiaoNfe::certificados()->get();
    }

    /**
     * Test that 422 errors throw ValidationException.
     */
    public function test_it_throws_validation_exception_on_422(): void
    {
        Http::fake([
            "api.test.com/*" => Http::response(['error' => 'Validation Error'], 422),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);
        $this->app->forgetInstance("espiaonfe");

        $this->expectException(ValidationException::class);

        EspiaoNfe::certificados()->get();
    }

    /**
     * Test that other errors throw EspiaoNfeException.
     */
    public function test_it_throws_espiaonfe_exception_on_500(): void
    {
        Http::fake([
            "api.test.com/*" => Http::response(['error' => 'Server Error'], 500),
        ]);

        config(["espiaonfe.base_uri" => "https://api.test.com"]);
        $this->app->forgetInstance("espiaonfe");

        $this->expectException(\Jcf\EspiaoNfe\Exceptions\EspiaoNfeException::class);

        EspiaoNfe::certificados()->get();
    }
}

