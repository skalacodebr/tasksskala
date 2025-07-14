<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Projeto;
use App\Observers\ProjetoObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Projeto::observe(ProjetoObserver::class);
    }
}
