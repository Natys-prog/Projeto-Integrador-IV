<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Epi;
use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class EpiController extends Controller
{
    /**
     * Listar todos os EPIs com filtros e paginação
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Epi::with('funcionario');

            // Filtros
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('tipo')) {
                $query->where('tipo', 'like', '%' . $request->tipo . '%');
            }

            if ($request->has('funcionario_id')) {
                $query->where('funcionario_id', $request->funcionario_id);
            }

            if ($request->has('vencimento_proximo')) {
                $dias = $request->get('dias', 30);
                $query->proximosVencimento($dias);
            }

            if ($request->has('vencidos')) {
                $query->where('data_vencimento', '<', now());
            }

            // Ordenação
            $orderBy = $request->get('order_by', 'created_at');
            $orderDirection = $request->get('order_direction', 'desc');
            $query->orderBy($orderBy, $orderDirection);

            // Paginação ou todos
            if ($request->has('paginate') && $request->paginate === 'false') {
                $epis = $query->get();
                return response()->json([
                    'success' => true,
                    'message' => 'EPIs recuperados com sucesso',
                    'data' => $epis
                ]);
            } else {
                $perPage = $request->get('per_page', 15);
                $epis = $query->paginate($perPage);
                return response()->json([
                    'success' => true,
                    'message' => 'EPIs recuperados com sucesso',
                    'data' => $epis->items(),
                    'pagination' => [
                        'current_page' => $epis->currentPage(),
                        'last_page' => $epis->lastPage(),
                        'per_page' => $epis->perPage(),
                        'total' => $epis->total(),
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao recuperar EPIs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Criar novo EPI
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'tipo' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'codigo' => 'required|string|max:255|unique:epis,codigo',
                'data_aquisicao' => 'required|date',
                'data_vencimento' => 'nullable|date|after_or_equal:today',
                'status' => 'required|in:ativo,inativo,manutencao,descartado',
                'fabricante' => 'nullable|string|max:255',
                'lote' => 'nullable|string|max:255',
                'funcionario_id' => 'nullable|exists:funcionarios,id',
            ]);

            $epi = Epi::create($validated);
            $epi->load('funcionario');

            return response()->json([
                'success' => true,
                'message' => 'EPI cadastrado com sucesso!',
                'data' => $epi
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados de entrada inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar EPI',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar um EPI específico
     */
    public function show($id): JsonResponse
    {
        try {
            $epi = Epi::with('funcionario')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $epi
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'EPI não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar EPI',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar EPI
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $epi = Epi::findOrFail($id);

            $validated = $request->validate([
                'nome' => 'sometimes|required|string|max:255',
                'tipo' => 'sometimes|required|string|max:255',
                'descricao' => 'sometimes|nullable|string',
                'codigo' => 'sometimes|required|string|max:255|unique:epis,codigo,' . $id,
                'data_aquisicao' => 'sometimes|required|date',
                'data_vencimento' => 'sometimes|nullable|date|after_or_equal:today',
                'status' => 'sometimes|required|in:ativo,inativo,manutencao,descartado',
                'fabricante' => 'sometimes|nullable|string|max:255',
                'lote' => 'sometimes|nullable|string|max:255',
                'funcionario_id' => 'sometimes|nullable|exists:funcionarios,id',
            ]);

            $epi->update($validated);
            $epi->load('funcionario');

            return response()->json([
                'success' => true,
                'message' => 'EPI atualizado com sucesso!',
                'data' => $epi
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'EPI não encontrado'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados de entrada inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar EPI',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deletar EPI (Soft Delete)
     */
    public function destroy($id): JsonResponse
    {
        try {
            $epi = Epi::findOrFail($id);
            $epi->delete();

            return response()->json([
                'success' => true,
                'message' => 'EPI deletado com sucesso!',
                'deleted_at' => $epi->deleted_at
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'EPI não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar EPI',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restaurar EPI deletado
     */
    public function restore($id): JsonResponse
    {
        try {
            $epi = Epi::withTrashed()->findOrFail($id);
            
            if (!$epi->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'EPI não está deletado'
                ], 400);
            }

            $epi->restore();
            $epi->load('funcionario');

            return response()->json([
                'success' => true,
                'message' => 'EPI restaurado com sucesso!',
                'data' => $epi
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'EPI não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao restaurar EPI',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deletar permanentemente (Hard Delete)
     */
    public function forceDelete($id): JsonResponse
    {
        try {
            $epi = Epi::withTrashed()->findOrFail($id);
            $epi->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'EPI deletado permanentemente!'
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'EPI não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar permanentemente EPI',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atribuir EPI a um funcionário
     */
    public function assign(Request $request, $id): JsonResponse
    {
        try {
            $epi = Epi::findOrFail($id);
            
            $validated = $request->validate([
                'funcionario_id' => 'required|exists:funcionarios,id'
            ]);

            $funcionario = Funcionario::findOrFail($validated['funcionario_id']);
            
            $epi->update([
                'funcionario_id' => $funcionario->id,
                'status' => 'ativo'
            ]);

            $epi->load('funcionario');

            return response()->json([
                'success' => true,
                'message' => "EPI atribuído ao funcionário {$funcionario->nome} com sucesso!",
                'data' => $epi
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'EPI ou funcionário não encontrado'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atribuir EPI',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remover atribuição de EPI
     */
    public function unassign($id): JsonResponse
    {
        try {
            $epi = Epi::findOrFail($id);
            
            $epi->update([
                'funcionario_id' => null,
                'status' => 'inativo'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'EPI desatribuído com sucesso!',
                'data' => $epi
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'EPI não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao desatribuir EPI',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar EPIs próximos ao vencimento
     */
    public function expiringAlert(Request $request): JsonResponse
    {
        try {
            $dias = $request->get('dias', 30);
            
            $epis = Epi::with('funcionario')
                ->proximosVencimento($dias)
                ->get();

            return response()->json([
                'success' => true,
                'message' => "EPIs que vencem em {$dias} dias",
                'data' => $epis,
                'count' => $epis->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar EPIs próximos ao vencimento',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estatísticas dos EPIs
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total' => Epi::count(),
                'ativos' => Epi::where('status', 'ativo')->count(),
                'inativos' => Epi::where('status', 'inativo')->count(),
                'manutencao' => Epi::where('status', 'manutencao')->count(),
                'descartados' => Epi::where('status', 'descartado')->count(),
                'vencidos' => Epi::where('data_vencimento', '<', now())->count(),
                'proximos_vencimento' => Epi::proximosVencimento(30)->count(),
                'sem_funcionario' => Epi::whereNull('funcionario_id')->count(),
                'deletados' => Epi::onlyTrashed()->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao calcular estatísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
