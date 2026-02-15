<?php

namespace App\Providers;

use App\Domain\Services\ScriptFormatter;
use App\Domain\Services\TLDRGeneratorService;
use App\Infrastructure\AI\AIClient;
use App\Infrastructure\Repositories\ScriptRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AIClient::class);
        $this->app->singleton(ScriptFormatter::class);
        $this->app->singleton(TLDRGeneratorService::class);
        $this->app->singleton(ScriptRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
