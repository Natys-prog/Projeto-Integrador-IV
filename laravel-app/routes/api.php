<?php

use App\Http\Controllers\Api\EpiController;
use Illuminate\Support\Facades\Route;

// Rotas de EPIs - API
Route::prefix('epis')->group(function () {
    Route::get('/', [EpiController::class, 'index']);           // GET /api/epis
    Route::post('/', [EpiController::class, 'store']);          // POST /api/epis
    Route::get('{id}', [EpiController::class, 'show']);         // GET /api/epis/1
    Route::put('{id}', [EpiController::class, 'update']);       // PUT /api/epis/1
    Route::delete('{id}', [EpiController::class, 'destroy']);   // DELETE /api/epis/1
    Route::post('{id}/restore', [EpiController::class, 'restore']);     // POST /api/epis/1/restore
    Route::delete('{id}/force', [EpiController::class, 'forceDelete']); // DELETE /api/epis/1/force
});