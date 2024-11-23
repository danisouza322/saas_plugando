<?php

use App\Livewire\Cliente\CreateCliente;
use App\Livewire\Cliente\EditCliente;
use App\Livewire\Cliente\IndexClientes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Empresa\CadastroEmpresa;
use App\Livewire\Empresa\EditarEmpresa;
use App\Livewire\User\EditProfile;
use App\Livewire\User\IndexUsers;
use App\Livewire\Certificado\IndexCertificados;
use App\Livewire\Dashboard\Index as DashboardIndex;
use App\Http\Controllers\HomeController;

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

// Rota inicial
Route::get('/', function() {
    if (Auth::check()) {
        return redirect()->route('painel.dashboard');
    }
    return view('auth.login');
})->name('root');

// Rota de cadastro
Route::get('/cadastro', function() {
    return view('empresa.cadastro');
})->name('cadastro.empresa');

// Rotas de autenticação
Auth::routes(['home' => 'painel.dashboard']);

// Rotas de tradução
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

// Rotas de atualização de perfil
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

// Rotas do painel (protegidas por autenticação)
Route::middleware(['auth', 'verified'])->prefix('painel')->name('painel.')->group(function () {
    // Dashboard (acessível por todos os usuários autenticados)
    Route::get('/', DashboardIndex::class)->name('dashboard');

    // Perfil e Empresa (acessível por todos os usuários autenticados)
    Route::get('/perfil', EditProfile::class)->name('perfil.editar');
    Route::get('/empresa/editar', EditarEmpresa::class)->name('empresa.editar');

    // Rotas que requerem super-admin
    Route::middleware(['role:super-admin'])->prefix('gerencial')->name('gerencial.')->group(function () {
        Route::get('/usuarios', \App\Livewire\User\IndexUsers::class)->name('usuarios.index');
        // Rota de gerenciamento de empresas (apenas para usuário ID 1)
        Route::get('/empresas', \App\Livewire\Admin\IndexEmpresas::class)->name('empresas.index');
    });

    // Clientes (acessível por super-admin e user)
    Route::prefix('clientes')->name('clientes.')->middleware(['check.cliente'])->group(function () {
        Route::get('/', IndexClientes::class)->name('index');
        Route::get('/create', CreateCliente::class)->name('create');
        Route::get('/{cliente}/edit', EditCliente::class)->name('edit')->where('cliente', '[0-9]+');
    });

    // Certificados (acessível por super-admin e user)
    Route::prefix('certificados')->name('certificados.')->middleware(['check.certificado'])->group(function () {
        Route::get('/', IndexCertificados::class)->name('index');
    });
});

// Rota catch-all (deve ser a última)
Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index')->where('any', '.*');
