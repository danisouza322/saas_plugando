<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsaasWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Webhook Asaas (sem autenticação)
Route::match(['get', 'post'], 'webhook/asaas', [AsaasWebhookController::class, 'handle'])
    ->middleware(['api'])
    ->withoutMiddleware([
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        \App\Http\Middleware\VerifyCsrfToken::class,
        \Illuminate\Auth\Middleware\Authenticate::class
    ]);

// Rotas que precisam de autenticação
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
