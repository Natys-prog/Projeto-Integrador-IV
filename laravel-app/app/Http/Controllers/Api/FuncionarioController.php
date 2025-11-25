<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class FuncionarioController extends Controller
{
    /**
     * Display a listing of the resource with filters and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Funcionario::with('epis');

            // Filtros
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('departamento')) {
                $query->where('departamento', 'like', '%' . $request->departamento . '%');
            }

            if ($request->has('cargo')) {
                $query->where('cargo', 'like', '%' . $request->cargo . '%');
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nome', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('cpf', 'like', '%' . $search . '%');
                });
            }

            // Ordenação
            $orderBy = $request->get('order_by', 'created_at');
            $orderDirection = $request->get('order_direction', 'desc');
            $query->orderBy($orderBy, $orderDirection);

            // Paginação ou todos
            if ($request->has('paginate') && $request->paginate === 'false') {
                $funcionarios = $query->get();
                return response()->json([
                    'success' => true,
                    'message' => 'Funcionários recuperados com sucesso',
                    'data' => $funcionarios
                ]);
            } else {
                $perPage = $request->get('per_page', 15);
                $funcionarios = $query->paginate($perPage);
                return response()->json([
                    'success' => true,
                    'message' => 'Funcionários recuperados com sucesso',
                    'data' => $funcionarios->items(),
                    'pagination' => [
                        'current_page' => $funcionarios->currentPage(),
                        'last_page' => $funcionarios->lastPage(),
                        'per_page' => $funcionarios->perPage(),
                        'total' => $funcionarios->total(),
                    ]
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao recuperar funcionários',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'cpf' => 'required|string|unique:funcionarios,cpf|max:14',
                'email' => 'required|email|unique:funcionarios,email',
                'telefone' => 'nullable|string|max:20',
                'departamento' => 'required|string|max:255',
                'cargo' => 'required|string|max:255',
                'data_admissao' => 'required|date',
                'data_demissao' => 'nullable|date|after:data_admissao',
                'status' => 'required|in:ativo,inativo,ferias,licenca',
                'endereco' => 'nullable|string',
                'cep' => 'nullable|string|max:9',
                'cidade' => 'nullable|string|max:255',
                'estado' => 'nullable|string|max:2',
            ]);

            $funcionario = Funcionario::create($validated);
            $funcionario->load('epis');

            return response()->json([
                'success' => true,
                'message' => 'Funcionário cadastrado com sucesso!',
                'data' => $funcionario
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
                'message' => 'Erro ao criar funcionário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        try {
            $funcionario = Funcionario::with(['epis', 'episAtivos', 'episVencidos'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $funcionario
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Funcionário não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar funcionário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $funcionario = Funcionario::findOrFail($id);

            $validated = $request->validate([
                'nome' => 'sometimes|required|string|max:255',
                'cpf' => 'sometimes|required|string|max:14|unique:funcionarios,cpf,' . $id,
                'email' => 'sometimes|required|email|unique:funcionarios,email,' . $id,
                'telefone' => 'sometimes|nullable|string|max:20',
                'departamento' => 'sometimes|required|string|max:255',
                'cargo' => 'sometimes|required|string|max:255',
                'data_admissao' => 'sometimes|required|date',
                'data_demissao' => 'sometimes|nullable|date|after:data_admissao',
                'status' => 'sometimes|required|in:ativo,inativo,ferias,licenca',
                'endereco' => 'sometimes|nullable|string',
                'cep' => 'sometimes|nullable|string|max:9',
                'cidade' => 'sometimes|nullable|string|max:255',
                'estado' => 'sometimes|nullable|string|max:2',
            ]);

            $funcionario->update($validated);
            $funcionario->load('epis');

            return response()->json([
                'success' => true,
                'message' => 'Funcionário atualizado com sucesso!',
                'data' => $funcionario
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Funcionário não encontrado'
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
                'message' => 'Erro ao atualizar funcionário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        try {
            $funcionario = Funcionario::findOrFail($id);
            
            // Verificar se tem EPIs ativos
            $episAtivos = $funcionario->episAtivos()->count();
            if ($episAtivos > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Não é possível deletar funcionário com {$episAtivos} EPI(s) ativo(s). Remova os EPIs primeiro."
                ], 400);
            }

            $funcionario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Funcionário deletado com sucesso!',
                'deleted_at' => $funcionario->deleted_at
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Funcionário não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar funcionário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id): JsonResponse
    {
        try {
            $funcionario = Funcionario::withTrashed()->findOrFail($id);
            
            if (!$funcionario->trashed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Funcionário não está deletado'
                ], 400);
            }

            $funcionario->restore();
            $funcionario->load('epis');

            return response()->json([
                'success' => true,
                'message' => 'Funcionário restaurado com sucesso!',
                'data' => $funcionario
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Funcionário não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao restaurar funcionário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deletar permanentemente
     */
    public function forceDelete($id): JsonResponse
    {
        try {
            $funcionario = Funcionario::withTrashed()->findOrFail($id);
            $funcionario->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Funcionário deletado permanentemente!'
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Funcionário não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar permanentemente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Listar EPIs do funcionário
     */
    public function epis($id): JsonResponse
    {
        try {
            $funcionario = Funcionario::findOrFail($id);
            $epis = $funcionario->epis()->get();

            return response()->json([
                'success' => true,
                'message' => 'EPIs do funcionário recuperados com sucesso',
                'data' => $epis,
                'funcionario' => $funcionario->nome
            ]);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Funcionário não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar EPIs do funcionário',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Estatísticas dos funcionários
     */
    public function stats(): JsonResponse
    {
        try {
            $stats = [
                'total' => Funcionario::count(),
                'ativos' => Funcionario::where('status', 'ativo')->count(),
                'inativos' => Funcionario::where('status', 'inativo')->count(),
                'ferias' => Funcionario::where('status', 'ferias')->count(),
                'licenca' => Funcionario::where('status', 'licenca')->count(),
                'novos_30_dias' => Funcionario::novos(30)->count(),
                'com_epis_vencidos' => Funcionario::whereHas('epis', function($q) {
                    $q->where('data_vencimento', '<', now());
                })->count(),
                'deletados' => Funcionario::onlyTrashed()->count()
            ];

            $departamentos = Funcionario::select('departamento')
                ->groupBy('departamento')
                ->selectRaw('departamento, count(*) as total')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'geral' => $stats,
                    'por_departamento' => $departamentos
                ]
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
