<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoEpi;
use Illuminate\Http\Request;

class TipoEpiController extends Controller
{
    // Listar todos os tipos de EPI (API)
    public function index(Request $request)
    {
        try {
            $query = TipoEpi::query();

            // Filtrar apenas ativos por padrão
            if (!$request->has('incluir_inativos') || $request->incluir_inativos !== 'true') {
                $query->ativos();
            }

            $tiposEpi = $query->orderBy('nome', 'asc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $tiposEpi,
                'total' => $tiposEpi->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao listar tipos de EPI: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'data' => []
            ], 500);
        }
    }

    // Buscar um tipo de EPI específico (API)
    public function show($id)
    {
        try {
            $tipoEpi = TipoEpi::with('epis')->find($id);
            
            if (!$tipoEpi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo de EPI não encontrado',
                    'data' => null
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Tipo de EPI encontrado com sucesso',
                'data' => $tipoEpi
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar tipo de EPI: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'data' => null
            ], 500);
        }
    }
}