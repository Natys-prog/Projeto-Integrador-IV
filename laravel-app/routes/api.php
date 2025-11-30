<?php

use App\Http\Controllers\Api\EpiController;
use App\Http\Controllers\Api\FuncionarioController;
use App\Models\Funcionario;
use App\Models\TipoEpi;
use Illuminate\Support\Facades\Route;

// Rotas de EPIs - API
Route::prefix('epis')->group(function () {
    Route::get('/', [EpiController::class, 'index']);                    // GET /api/epis
    Route::post('/', [EpiController::class, 'store']);                   // POST /api/epis
    Route::get('/stats', [EpiController::class, 'stats']);               // GET /api/epis/stats
    Route::get('/expiring-alert', [EpiController::class, 'expiringAlert']); // GET /api/epis/expiring-alert
    Route::get('{id}', [EpiController::class, 'show']);                  // GET /api/epis/1
    Route::put('{id}', [EpiController::class, 'update']);                // PUT /api/epis/1
    Route::delete('{id}', [EpiController::class, 'destroy']);            // DELETE /api/epis/1
    Route::post('{id}/restore', [EpiController::class, 'restore']);      // POST /api/epis/1/restore
    Route::delete('{id}/force', [EpiController::class, 'forceDelete']);  // DELETE /api/epis/1/force
    Route::post('{id}/assign', [EpiController::class, 'assign']);        // POST /api/epis/1/assign
    Route::post('{id}/unassign', [EpiController::class, 'unassign']);    // POST /api/epis/1/unassign
});

// Rotas de Funcionários - API
Route::prefix('funcionarios')->group(function () {
    Route::get('/', [FuncionarioController::class, 'index']);             // GET /api/funcionarios
    Route::post('/', [FuncionarioController::class, 'store']);            // POST /api/funcionarios
    Route::get('/stats', [FuncionarioController::class, 'stats']);        // GET /api/funcionarios/stats
    Route::get('{id}', [FuncionarioController::class, 'show']);           // GET /api/funcionarios/1
    Route::put('{id}', [FuncionarioController::class, 'update']);         // PUT /api/funcionarios/1
    Route::delete('{id}', [FuncionarioController::class, 'destroy']);     // DELETE /api/funcionarios/1
    Route::post('{id}/restore', [FuncionarioController::class, 'restore']); // POST /api/funcionarios/1/restore
    Route::delete('{id}/force', [FuncionarioController::class, 'forceDelete']); // DELETE /api/funcionarios/1/force
    Route::get('{id}/epis', [FuncionarioController::class, 'epis']);       // GET /api/funcionarios/1/epis
});

// API para listar funcionários simples (para uso nos selects)
Route::get('/funcionarios-select', function () {
    $funcionarios = Funcionario::where('status', 'ativo')
                              ->with('departamento')
                              ->select('id', 'nome', 'departamento_id')
                              ->orderBy('nome')
                              ->get();
    
    return response()->json([
        'success' => true,
        'data' => $funcionarios
    ]);
});

// API para listar tipos de EPI
Route::get('/tipos-epi', function () {
    $tipos = TipoEpi::ativos()
                    ->select('id', 'nome', 'codigo', 'icone', 'cor')
                    ->orderBy('nome')
                    ->get();
    
    return response()->json([
        'success' => true,
        'data' => $tipos
    ]);
});

// APIs auxiliares
Route::get('/departamentos', function () {
    return App\Models\Departamento::ativo()
                  ->select('id', 'nome', 'codigo', 'cor')
                  ->orderBy('nome')
                  ->get();
});

Route::get('/cargos', function () {
    return App\Models\Cargo::ativo()
                  ->select('id', 'nome', 'codigo', 'nivel')
                  ->orderBy('nome')
                  ->get();
});

// Rota de teste para debug
Route::get('/funcionarios-test', function () {
    $funcionarios = App\Models\Funcionario::with(['departamento', 'cargo'])->get();
    return response()->json([
        'success' => true,
        'count' => $funcionarios->count(),
        'data' => $funcionarios,
        'debug' => 'Test route working'
    ]);
});