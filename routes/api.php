<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\PetsController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\RacaController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;



Route::get('/ping', function() {
    return ['pong' => true];
});

Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');

Route::post('/auth/login', [AuthController::class, 'login']); // acessivel apenas quando n está logado
Route::post('/auth/register', [AuthController::class, 'register']); // acessivel apenas quando n está logado

Route::middleware('auth:api')->group(function() { // apenas se estiver logado
    Route::post('/auth/validate', [AuthController::class, 'validateToken']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    
    Route::resource('/pets', 'App\Http\Controllers\PetsController');
    Route::resource('/funcionario', 'App\Http\Controllers\FuncionarioController');
    Route::resource('/agendamento', 'App\Http\Controllers\AgendamentoController');
    Route::resource('/user', 'App\Http\Controllers\UserController');

    // RAÇAS
    Route::get('/racas', [RacaController::class, 'getAll']);
    Route::post('/racas', [RacaController::class, 'insert']);
    Route::put('/racas/{id}', [RacaController::class, 'update']);
    Route::delete('/racas/{id}', [RacaController::class, 'delete']);

    // STATUS
    Route::get('/status', [StatusController::class, 'getAll']);
    Route::post('/status', [StatusController::class, 'insert']);
    Route::put('/status/{id}', [StatusController::class, 'update']);
    Route::delete('/status/{id}', [StatusController::class, 'delete']);

    // PRODUTOS
    Route::get('/produtos', [ProdutoController::class, 'getAll']);
    Route::post('/produtos', [ProdutoController::class, 'insert']);
    Route::put('/produtos/{id}', [ProdutoController::class, 'update']);
    Route::delete('/produtos/{id}', [ProdutoController::class, 'delete']);

    // CLIENTES
    Route::get('/clientes', [ClienteController::class, 'getAll']);
    Route::post('/clientes', [ClienteController::class, 'insert']);
    Route::put('/clientes/{id}', [ClienteController::class, 'update']);
    Route::delete('/clientes/{id}', [ClienteController::class, 'delete']);

});

