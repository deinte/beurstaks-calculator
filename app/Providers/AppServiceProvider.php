<?php

namespace App\Providers;

use App\Tob\Livewire\Calculator;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        // Register Livewire components from custom namespace
        // The component name must match what Livewire auto-derives from the class
        Livewire::component('app.tob.livewire.calculator', Calculator::class);
    }
}
