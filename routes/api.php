<?php

use App\Http\Controllers\FuncaoController;
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

Route::post('/auth/login', [AuthController::class, 'login']); // acessível apenas quando não está logado
Route::post('/auth/register', [AuthController::class, 'register']); // acessível apenas quando não está logado

// Grupo de rotas protegidas por middleware
Route::middleware(['auth:api', 'check.permissions'])->group(function() {
    // Rotas para autenticação e controle de usuário
    Route::post('/auth/validate', [AuthController::class, 'validateToken']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/index', [AuthController::class, 'index']);
    Route::delete('/auth/delete/{id}', [AuthController::class, 'delete']);

    // Rotas para PETS
    Route::get('/pets', [PetsController::class, 'getAll']);
    Route::post('/pets', [PetsController::class, 'insert']);
    Route::delete('/pets/{id}', [PetsController::class, 'delete']);

    // Rotas para AGENDAMENTO
    Route::get('/agendamentos', [AgendamentoController::class, 'getAll']);
    Route::post('/agendamentos', [AgendamentoController::class, 'insert']);
    Route::put('/agendamentos/{id}', [AgendamentoController::class, 'update']);
    Route::delete('/agendamentos/{id}', [AgendamentoController::class, 'delete']);

    // Rotas para RAÇAS
    Route::get('/racas', [RacaController::class, 'getAll']);
    Route::post('/racas', [RacaController::class, 'insert']);
    Route::put('/racas/{id}', [RacaController::class, 'update']);
    Route::delete('/racas/{id}', [RacaController::class, 'delete']);

    // Rotas para STATUS
    Route::get('/status', [StatusController::class, 'getAll']);
    Route::post('/status', [StatusController::class, 'insert']);
    Route::put('/status/{id}', [StatusController::class, 'update']);
    Route::delete('/status/{id}', [StatusController::class, 'delete']);

    // Rotas para PRODUTOS
    Route::get('/produtos', [ProdutoController::class, 'getAll']);
    Route::post('/produtos', [ProdutoController::class, 'insert']);
    Route::put('/produtos/{id}', [ProdutoController::class, 'update']);
    Route::delete('/produtos/{id}', [ProdutoController::class, 'delete']);

    // Rotas para CLIENTES
    Route::get('/clientes', [ClienteController::class, 'getAll']);
    Route::post('/clientes', [ClienteController::class, 'insert']);
    Route::put('/clientes/{id}', [ClienteController::class, 'update']);
    Route::delete('/clientes/{id}', [ClienteController::class, 'delete']);

    // Rotas para FUNCIONÁRIOS
    Route::get('/funcao', [FuncaoController::class, 'getAll']);
    Route::post('/funcao', [FuncaoController::class, 'insert']);
    Route::put('/funcao/{id}', [FuncaoController::class, 'update']);
    Route::delete('/funcao/{id}', [FuncaoController::class, 'delete']);
});
