<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Orchestrator;
use App\Services\HttpOrchestrator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Orchestrator::class, HttpOrchestrator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
