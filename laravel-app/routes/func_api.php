<?php

use App\Http\Controllers\Api\EpiController;
use App\Http\Controllers\Api\FuncionarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
// Rotas de Funcionários 
Route::get('/funcionarios', [FuncionarioController::class, 'index']);
Route::get('/funcionarios/{id}', [FuncionarioController::class, 'show']);
Route::post('/funcionarios', [FuncionarioController::class, 'store']);
Route::put('/funcionarios/{id}', [FuncionarioController::class, 'update']);
Route::delete('/funcionarios/{id}', [FuncionarioController::class, 'destroy']);
Route::post('/funcionarios/{id}/restore', [FuncionarioController::class, 'restore']); 

});
