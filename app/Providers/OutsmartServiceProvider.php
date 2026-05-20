<?php

namespace App\Providers;

use App\Services\OutsmartService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class OutsmartServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(OutsmartService::class, function () {
            return new OutsmartService(
                new Client([
                    'base_uri' => rtrim(config('outsmart.base_url'), '/').'/',
                    'headers' => [
                        'Accept' => 'application/json',
                    ],
                ])
            );
        });
    }
}
