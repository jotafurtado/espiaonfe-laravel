<?php

namespace Jcf\EspiaoNfe\Tests;

use Jcf\EspiaoNfe\Providers\EspiaoNfeServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [EspiaoNfeServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app["config"]->set("espiaonfe.esp_cloud_token", "fake");
        $app["config"]->set("espiaonfe.user_token", "fake");
        $app["config"]->set("espiaonfe.base_uri", "fake");
    }
}
