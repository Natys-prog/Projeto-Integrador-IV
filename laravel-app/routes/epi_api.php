<?php

use App\Http\Controllers\Api\EpiController;
use App\Http\Controllers\Api\TipoEpiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Rotas de EPIs
    Route::get('/epis', [EpiController::class, 'index']);
    Route::get('/epis/{id}', [EpiController::class, 'show']);
    Route::post('/epis', [EpiController::class, 'store']);
    Route::put('/epis/{id}', [EpiController::class, 'update']);
    Route::delete('/epis/{id}', [EpiController::class, 'destroy']);
    Route::post('/epis/{id}/restore', [EpiController::class, 'restore']);
    Route::delete('/epis/{id}/force', [EpiController::class, 'forceDelete']);
    
    // Rotas de Tipos de EPI
    Route::get('/tipos-epi', [TipoEpiController::class, 'index']);
    Route::get('/tipos-epi/{id}', [TipoEpiController::class, 'show']);
});