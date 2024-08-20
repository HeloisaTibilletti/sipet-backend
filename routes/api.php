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
    Route::post('/auth/validate'. [AuthController::class, 'validateToken']);
    Route::post('/auth/logout'. [AuthController::class, 'logout']);

    
    Route::resource('/pets', 'App\Http\Controllers\PetsController');
    Route::resource('/funcionario', 'App\Http\Controllers\FuncionarioController');
    Route::resource('/cliente', 'App\Http\Controllers\ClienteController');
    Route::resource('/status', 'App\Http\Controllers\StatusController');
    Route::resource('/agendamento', 'App\Http\Controllers\AgendamentoController');
    Route::resource('/produtos', 'App\Http\Controllers\ProdutosController');
    Route::resource('/user', 'App\Http\Controllers\UserController');
});

