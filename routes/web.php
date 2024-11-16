<?php

use App\Livewire\Cliente\CreateCliente;
use App\Livewire\Cliente\EditCliente;
use App\Livewire\Cliente\IndexClientes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Empresa\CadastroEmpresa;
use App\Livewire\Empresa\EditarEmpresa;
use App\Livewire\Task\TaskList;
use App\Livewire\Task\TaskTemplateList;
use App\Livewire\User\EditProfile;
use App\Livewire\Certificado\IndexCertificados;
use App\Livewire\Dashboard\Index;

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
Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

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
Route::middleware(['auth'])->prefix('painel')->name('painel.')->group(function () {
    // Dashboard
    Route::get('/', \App\Livewire\Dashboard\Index::class)->name('dashboard');

    // Perfil
    Route::get('/perfil', EditProfile::class)->name('perfil.editar');
    
    // Empresa
    Route::get('/empresa/editar', EditarEmpresa::class)->name('empresa.editar');

    // Clientes
    Route::get('/clientes', IndexClientes::class)->name('clientes.index');
    Route::get('/clientes/novo', CreateCliente::class)->name('clientes.create');
    Route::get('/clientes/editar/{clienteId}', EditCliente::class)->name('clientes.edit');

    // Certificados
    Route::get('/certificados', IndexCertificados::class)->name('certificados.index');

    // Tasks
    Route::get('/tarefas', TaskList::class)->name('tarefas.index');
    Route::get('/tarefas/modelos', TaskTemplateList::class)->name('tarefas.modelos.index');
});

// Rota catch-all (deve ser a última)
Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index')->where('any', '.*');
