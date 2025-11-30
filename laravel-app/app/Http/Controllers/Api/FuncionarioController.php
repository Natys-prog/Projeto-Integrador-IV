<?php
// Executar: php artisan make:controller Api/FuncionarioController
// filepath: c:\Users\mrros\source\repos\Projeto-Integrador-IV\laravel-app\app\Http\Controllers\Api\FuncionarioController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use App\Models\Departamento;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FuncionarioController extends Controller
{
    // Listar funcionários
    public function index(Request $request)
    {
        try {
            $query = Funcionario::with(['departamento', 'cargo']);

            // Filtros
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('departamento_id')) {
                $query->where('departamento_id', $request->departamento_id);
            }

            if ($request->filled('cargo_id')) {
                $query->where('cargo_id', $request->cargo_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nome', 'like', '%' . $search . '%')
                      ->orWhere('matricula', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('cpf', 'like', '%' . $search . '%')
                      ->orWhereHas('departamento', function($dq) use ($search) {
                          $dq->where('nome', 'like', '%' . $search . '%');
                      })
                      ->orWhereHas('cargo', function($cq) use ($search) {
                          $cq->where('nome', 'like', '%' . $search . '%');
                      });
                });
            }

            // Ordenação
            $orderBy = $request->get('order_by', 'created_at');
            $orderDirection = $request->get('order_direction', 'desc');
            
            $validOrderBy = ['created_at', 'nome', 'matricula', 'data_admissao', 'status'];
            if (!in_array($orderBy, $validOrderBy)) {
                $orderBy = 'created_at';
            }

            $query->orderBy($orderBy, $orderDirection);

            $funcionarios = $query->get();

            // Estatísticas
            $statistics = [
                'total' => $funcionarios->count(),
                'ativo' => $funcionarios->where('status', 'ativo')->count(),
                'inativo' => $funcionarios->where('status', 'inativo')->count(),
                'afastado' => $funcionarios->where('status', 'afastado')->count(),
                'demitido' => $funcionarios->where('status', 'demitido')->count(),
            ];
            
            return response()->json([
                'success' => true,
                'data' => $funcionarios,
                'statistics' => $statistics,
                'filters_applied' => [
                    'status' => $request->get('status'),
                    'departamento_id' => $request->get('departamento_id'),
                    'cargo_id' => $request->get('cargo_id'),
                    'search' => $request->get('search'),
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar funcionários'
            ], 500);
        }
    }

    // Mostrar funcionário específico
    public function show($id)
    {
        try {
            $funcionario = Funcionario::with(['departamento', 'cargo', 'epis.tipoEpi'])->find($id);
            
            if (!$funcionario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Funcionário não encontrado'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $funcionario
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar funcionário'
            ], 500);
        }
    }

    // Criar funcionário
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'matricula' => 'nullable|string|max:20|unique:funcionarios,matricula',
                'email' => 'required|email|unique:funcionarios,email',
                'cpf' => 'nullable|string|max:14|unique:funcionarios,cpf',
                'rg' => 'nullable|string|max:20',
                'data_nascimento' => 'nullable|date|before:today',
                'genero' => 'nullable|in:masculino,feminino,outro,prefiro_nao_informar',
                'telefone' => 'nullable|string|max:20',
                'telefone_emergencia' => 'nullable|string|max:20',
                'contato_emergencia' => 'nullable|string|max:100',
                'endereco' => 'nullable|string|max:255',
                'endereco_completo' => 'nullable|string',
                'cep' => 'nullable|string|max:10',
                'cidade' => 'nullable|string|max:100',
                'estado' => 'nullable|string|max:2',
                'departamento_id' => 'nullable|exists:departamentos,id',
                'cargo_id' => 'nullable|exists:cargos,id',
                'status' => 'nullable|in:ativo,inativo,afastado,demitido',
                'data_admissao' => 'nullable|date',
                'observacoes' => 'nullable|string',
            ]);

            $validated['status'] = $validated['status'] ?? 'ativo';
            $funcionario = Funcionario::create($validated);
            $funcionario->load(['departamento', 'cargo']);

            return response()->json([
                'success' => true,
                'message' => 'Funcionário cadastrado com sucesso!',
                'data' => $funcionario
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

    // Atualizar funcionário
    public function update(Request $request, $id)
    {
        try {
            $funcionario = Funcionario::find($id);
            
            if (!$funcionario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Funcionário não encontrado'
                ], 404);
            }

            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'matricula' => ['nullable', 'string', 'max:20', Rule::unique('funcionarios')->ignore($id)],
                'email' => ['required', 'email', Rule::unique('funcionarios')->ignore($id)],
                'cpf' => ['nullable', 'string', 'max:14', Rule::unique('funcionarios')->ignore($id)],
                'rg' => 'nullable|string|max:20',
                'data_nascimento' => 'nullable|date|before:today',
                'genero' => 'nullable|in:masculino,feminino,outro,prefiro_nao_informar',
                'telefone' => 'nullable|string|max:20',
                'telefone_emergencia' => 'nullable|string|max:20',
                'contato_emergencia' => 'nullable|string|max:100',
                'endereco' => 'nullable|string|max:255',
                'endereco_completo' => 'nullable|string',
                'cep' => 'nullable|string|max:10',
                'cidade' => 'nullable|string|max:100',
                'estado' => 'nullable|string|max:2',
                'departamento_id' => 'nullable|exists:departamentos,id',
                'cargo_id' => 'nullable|exists:cargos,id',
                'status' => 'nullable|in:ativo,inativo,afastado,demitido',
                'data_admissao' => 'nullable|date',
                'observacoes' => 'nullable|string',
            ]);

            $funcionario->update($validated);
            $funcionario->load(['departamento', 'cargo']);

            return response()->json([
                'success' => true,
                'message' => 'Funcionário atualizado com sucesso!',
                'data' => $funcionario
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

    // Deletar funcionário
    public function destroy($id)
    {
        try {
            $funcionario = Funcionario::find($id);
            
            if (!$funcionario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Funcionário não encontrado'
                ], 404);
            }

            // Verificar se tem EPIs atribuídos
            if ($funcionario->epis()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Não é possível excluir funcionário com EPIs atribuídos'
                ], 422);
            }

            $funcionario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Funcionário excluído com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir funcionário'
            ], 500);
        }
    }

    // Restaurar funcionário
    public function restore($id)
    {
        try {
            $funcionario = Funcionario::withTrashed()->find($id);
            
            if (!$funcionario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Funcionário não encontrado'
                ], 404);
            }

            $funcionario->restore();

            return response()->json([
                'success' => true,
                'message' => 'Funcionário restaurado com sucesso!',
                'data' => $funcionario->load(['departamento', 'cargo'])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao restaurar funcionário'
            ], 500);
        }
    }
}
