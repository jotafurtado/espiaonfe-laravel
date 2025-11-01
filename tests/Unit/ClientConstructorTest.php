<?php

namespace Jcf\EspiaoNfe\Tests\Unit;

use Jcf\EspiaoNfe\Exceptions\EspiaoNfeException;
use Jcf\EspiaoNfe\Http\Client;
use Jcf\EspiaoNfe\Tests\TestCase;

class ClientConstructorTest extends TestCase
{
    /**
     * Test that the client throws an exception if credentials are not provided.
     */
    public function test_it_throws_an_exception_if_credentials_are_not_provided(): void
    {
        $this->expectException(EspiaoNfeException::class);

        new Client('', '', 'https://api.test.com');
    }
}
