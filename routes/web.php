<?php

use App\Livewire\Cliente\CreateCliente;
use App\Livewire\Cliente\EditCliente;
use App\Livewire\Cliente\IndexClientes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Empresa\CadastroEmpresa;
use App\Livewire\Empresa\EditarEmpresa;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/cadastro', CadastroEmpresa::class)->name('cadastro.empresa');

Auth::routes();

//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');


Route::middleware(['auth'])->prefix('painel')->name('painel.')->group(function () {
    // Rotas relacionadas à empresa
    Route::get('/empresa/editar', EditarEmpresa::class)->name('empresa.editar');

    // Rotas relacionadas aos clientes
    Route::get('/clientes', IndexClientes::class)->name('clientes.index');
    Route::get('/clientes/novo', CreateCliente::class)->name('clientes.create');
    Route::get('/clientes/editar/{id}', EditCliente::class)->name('clientes.edit');

    // Você pode adicionar outras rotas aqui
});




