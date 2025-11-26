<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Epi;
use App\Models\TipoEpi;
use Illuminate\Http\Request;

class EpiController extends Controller
{
    // Listar todos os EPIs (API)
    public function index(Request $request)
    {
        $query = Epi::with(['funcionario', 'tipoEpi']);

        // Aplicar filtros
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('tipo_epi_id')) {
            $query->where('tipo_epi_id', $request->tipo_epi_id);
        }

        // Filtro por código de tipo (compatibilidade)
        if ($request->has('tipo')) {
            $tipoEpi = TipoEpi::where('codigo', $request->tipo)->first();
            if ($tipoEpi) {
                $query->where('tipo_epi_id', $tipoEpi->id);
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%')
                  ->orWhere('codigo', 'like', '%' . $search . '%')
                  ->orWhere('fabricante', 'like', '%' . $search . '%')
                  ->orWhereHas('tipoEpi', function($tq) use ($search) {
                      $tq->where('nome', 'like', '%' . $search . '%');
                  });
            });
        }

        $epis = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $epis
        ]);
    }

    // Buscar um EPI específico (API)
    public function show($id)
    {
        $epi = Epi::with('funcionario')->find($id);
        
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

    // Criar novo EPI (API)
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'tipo_epi_id' => 'required|exists:tipos_epi,id',
                'codigo' => 'required|string|unique:epis,codigo',
                'status' => 'nullable|string|in:ativo,inativo,manutencao,descartado',
                'fabricante' => 'nullable|string|max:255',
                'lote' => 'nullable|string|max:255',
                'funcionario_id' => 'nullable|exists:funcionarios,id',
                'data_aquisicao' => 'nullable|date',
                'data_vencimento' => 'nullable|date',
                'descricao' => 'nullable|string',
            ]);

            $validated['status'] = $validated['status'] ?? 'ativo';
            $epi = Epi::create($validated);
            $epi->load(['tipoEpi', 'funcionario']);

            return response()->json([
                'success' => true,
                'message' => 'EPI cadastrado com sucesso!',
                'data' => $epi
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    // Atualizar EPI (API)
    public function update(Request $request, $id)
    {
        try {
            $epi = Epi::find($id);
            
            if (!$epi) {
                return response()->json([
                    'success' => false,
                    'message' => 'EPI não encontrado'
                ], 404);
            }

            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'tipo_epi_id' => 'required|exists:tipos_epi,id',
                'codigo' => 'required|string|unique:epis,codigo,' . $id,
                'status' => 'nullable|string|in:ativo,inativo,manutencao,descartado',
                'fabricante' => 'nullable|string|max:255',
                'lote' => 'nullable|string|max:255',
                'funcionario_id' => 'nullable|exists:funcionarios,id',
                'data_aquisicao' => 'nullable|date',
                'data_vencimento' => 'nullable|date',
                'descricao' => 'nullable|string',
            ]);

            $epi->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'EPI atualizado com sucesso!',
                'data' => $epi->load('funcionario')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    // Deletar EPI (API)
    public function destroy($id)
    {
        try {
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
                'message' => 'EPI deletado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar EPI'
            ], 500);
        }
    }

    // Restaurar EPI deletado
    public function restore($id)
    {
        try {
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

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao restaurar EPI'
            ], 500);
        }
    }
}
