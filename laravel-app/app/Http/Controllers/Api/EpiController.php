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
        try {
            $query = Epi::with(['funcionario', 'tipoEpi']);

            // Aplicar filtros
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('tipo_epi_id')) {
                $query->where('tipo_epi_id', $request->tipo_epi_id);
            }

            // Filtro por código de tipo (compatibilidade)
            if ($request->filled('tipo')) {
                $tipoEpi = TipoEpi::where('codigo', $request->tipo)->first();
                if ($tipoEpi) {
                    $query->where('tipo_epi_id', $tipoEpi->id);
                }
            }

            if ($request->filled('search')) {
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

            // Ordenação
            $orderBy = $request->get('order_by', 'created_at');
            $orderDirection = $request->get('order_direction', 'desc');
            
            $validOrderBy = ['created_at', 'nome', 'tipo', 'data_vencimento'];
            if (!in_array($orderBy, $validOrderBy)) {
                $orderBy = 'created_at';
            }

            if ($orderBy === 'tipo') {
                $query->join('tipos_epi', 'epis.tipo_epi_id', '=', 'tipos_epi.id')
                      ->orderBy('tipos_epi.nome', $orderDirection)
                      ->select('epis.*');
            } else {
                $query->orderBy($orderBy, $orderDirection);
            }

            $epis = $query->get();
            
            // Statistics
            $statistics = [
                'total' => $epis->count(),
                'ativo' => $epis->where('status', 'ativo')->count(),
                'inativo' => $epis->where('status', 'inativo')->count(),
                'manutencao' => $epis->where('status', 'manutencao')->count(),
                'descartado' => $epis->where('status', 'descartado')->count(),
                'vencidos' => $epis->filter(function($epi) {
                    return $epi->data_vencimento && $epi->data_vencimento->isPast();
                })->count(),
                'vencendo_30_dias' => $epis->filter(function($epi) {
                    return $epi->data_vencimento && 
                           $epi->data_vencimento->isFuture() && 
                           $epi->data_vencimento->diffInDays(now()) <= 30;
                })->count()
            ];
            
            return response()->json([
                'success' => true,
                'data' => $epis,
                'statistics' => $statistics,
                'filters_applied' => [
                    'status' => $request->get('status'),
                    'tipo_epi_id' => $request->get('tipo_epi_id'),
                    'search' => $request->get('search'),
                    'order_by' => $orderBy,
                    'order_direction' => $orderDirection
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao listar EPIs: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'data' => [],
                'statistics' => []
            ], 500);
        }
    }

    // Buscar um EPI específico (API)
    public function show($id)
    {
        try {
            $epi = Epi::with(['funcionario', 'tipoEpi'])->find($id);
            
            if (!$epi) {
                return response()->json([
                    'success' => false,
                    'message' => 'EPI não encontrado',
                    'data' => null
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'EPI encontrado com sucesso',
                'data' => $epi
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar EPI: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'data' => null
            ], 500);
        }
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
            $validated['data_aquisicao'] = $validated['data_aquisicao'] ?? now()->format('Y-m-d');
            
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
            \Log::error('Erro ao criar EPI: ' . $e->getMessage());
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
            \Log::info("=== INÍCIO ATUALIZAÇÃO EPI ===");
            \Log::info("EPI ID recebido: {$id} (tipo: " . gettype($id) . ")");
            \Log::info("Dados da requisição:", $request->all());
            \Log::info("Headers da requisição:", $request->headers->all());
            \Log::info("Método HTTP:", $request->method());
            \Log::info("URL completa:", $request->fullUrl());
            
            $epi = Epi::find($id);
            
            if (!$epi) {
                \Log::warning("EPI não encontrado: {$id}");
                return response()->json([
                    'success' => false,
                    'message' => 'EPI não encontrado'
                ], 404);
            }

            \Log::info("EPI atual:", ['current_epi' => $epi->toArray()]);

            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'tipo_epi_id' => 'required|exists:tipos_epi,id',
                'codigo' => 'required|string|unique:epis,codigo,' . $id . ',id',
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
                'data' => $epi->load(['funcionario', 'tipoEpi'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error("Erro de validação ao atualizar EPI {$id}:", [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            $errorMessages = collect($e->errors())->flatten()->implode('; ');
            
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . $errorMessages,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error("Erro geral ao atualizar EPI {$id}: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
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
