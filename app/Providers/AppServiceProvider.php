<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Livewire\Empresa\ValidacaoDados;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Registrar o UserObserver
        \App\Models\User::observe(\App\Observers\UserObserver::class);
        
        // Registrar componentes Livewire
        Livewire::component('empresa.validacao-dados', ValidacaoDados::class);
        Livewire::component('empresa.dados-empresa', \App\Http\Livewire\Empresa\DadosEmpresa::class);
        
        Schema::defaultStringLength(191);
    }
}
