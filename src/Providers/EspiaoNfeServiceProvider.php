<?php

namespace Jcf\EspiaoNfe\Providers;

use Illuminate\Support\ServiceProvider;

class EspiaoNfeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/espiaonfe.php',
            'espiaonfe'
        );

        $this->app->singleton('espiaonfe', function ($app) {
            $config = $app['config']['espiaonfe'];

            return new \Jcf\EspiaoNfe\Http\Client(
                $config['esp_cloud_token'],
                $config['user_token'],
                $config['base_uri'],
                $config['timeout'] ?? 30,
                $config['retry'] ?? 3,
                $config['retry_delay'] ?? 100,
                $config['log_requests'] ?? false,
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/espiaonfe.php' => config_path('espiaonfe.php'),
        ], 'config');
    }
}
