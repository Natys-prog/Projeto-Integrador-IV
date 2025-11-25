<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Epi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EpiController extends Controller
{
    /**
     * Listar todos os EPIs
     */
    public function index(): JsonResponse
    {
        $epis = Epi::all();
        return response()->json([
            'success' => true,
            'message' => 'EPIs recuperados com sucesso',
            'data' => $epis
        ]);
    }

    /**
     * Criar novo EPI
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|string',
            'categoria' => 'required|string',
            'quantidade' => 'required|integer|min:0',
            'data_validade' => 'nullable|date',
            'data_aquisicao' => 'nullable|date',
            'fabricante' => 'nullable|string',
            'modelo' => 'nullable|string',
            'norma' => 'nullable|string',
            'descricao' => 'nullable|string',
        ]);

        $epi = Epi::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'EPI cadastrado com sucesso!',
            'data' => $epi
        ], 201);
    }

    /**
     * Mostrar um EPI específico
     */
    public function show($id): JsonResponse
    {
        $epi = Epi::find($id);

        if (!$epi) {
            return response()->json([
                'success' => false,
                'message' => 'EPI não encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $epi
        ]);
    }

    /**
     * Atualizar EPI
     */
    public function update(Request $request, $id): JsonResponse
    {
        $epi = Epi::find($id);

        if (!$epi) {
            return response()->json([
                'success' => false,
                'message' => 'EPI não encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'tipo' => 'sometimes|required|string',
            'categoria' => 'sometimes|required|string',
            'quantidade' => 'sometimes|required|integer|min:0',
            'data_validade' => 'nullable|date',
            'data_aquisicao' => 'nullable|date',
            'fabricante' => 'nullable|string',
            'modelo' => 'nullable|string',
            'norma' => 'nullable|string',
            'descricao' => 'nullable|string',
        ]);

        $epi->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'EPI atualizado com sucesso!',
            'data' => $epi
        ]);
    }

    /**
     * Deletar EPI (Soft Delete)
     */
    public function destroy($id): JsonResponse
    {
        $epi = Epi::find($id);

        if (!$epi) {
            return response()->json([
                'success' => false,
                'message' => 'EPI não encontrado'
            ], 404);
        }

        $epi->delete();

        return response()->json([
            'success' => true,
            'message' => 'EPI deletado com sucesso!',
            'deleted_at' => $epi->deleted_at
        ]);
    }

    /**
     * Restaurar EPI deletado
     */
    public function restore($id): JsonResponse
    {
        $epi = Epi::withTrashed()->find($id);

        if (!$epi) {
            return response()->json([
                'success' => false,
                'message' => 'EPI não encontrado'
            ], 404);
        }

        $epi->restore();

        return response()->json([
            'success' => true,
            'message' => 'EPI restaurado com sucesso!',
            'data' => $epi
        ]);
    }

    /**
     * Deletar permanentemente (Hard Delete)
     */
    public function forceDelete($id): JsonResponse
    {
        $epi = Epi::withTrashed()->find($id);

        if (!$epi) {
            return response()->json([
                'success' => false,
                'message' => 'EPI não encontrado'
            ], 404);
        }

        $epi->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'EPI deletado permanentemente!'
        ]);
    }
}
