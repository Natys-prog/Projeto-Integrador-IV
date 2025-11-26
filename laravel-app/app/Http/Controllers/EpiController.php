<?php

namespace App\Http\Controllers;

use App\Models\Epi;
use Illuminate\Http\Request;

class EpiController extends Controller
{
    // Listar todos os EPIs com paginação e filtros (para views web)
    public function index(Request $request)
    {
        $query = Epi::with('funcionario');

        // Filtros opcionais
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('tipo_epi_id') && $request->tipo_epi_id != '') {
            $query->where('tipo_epi_id', $request->tipo_epi_id);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%')
                  ->orWhere('codigo', 'like', '%' . $search . '%')
                  ->orWhere('fabricante', 'like', '%' . $search . '%');
            });
        }

        // Ordenação
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        // Paginação (15 itens por página)
        $perPage = $request->get('per_page', 15);
        $epis = $query->paginate($perPage);

        // Manter parâmetros da query string na paginação
        $epis->appends($request->query());

        return view('epi', [
            'epis' => $epis,
            'filters' => $request->only(['status', 'tipo_epi_id', 'search', 'order_by', 'order_direction']),
            'serverTime' => date('Y-m-d H:i:s')
        ]);
    }

    // Mostrar formulário de criação
    public function create()
    {
        return view('epi.create');
    }

    // Salvar novo EPI
    public function store(Request $request)
    {
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

        if ($request->expectsJson()) {
            return response()->json(['message' => 'EPI cadastrado com sucesso!', 'epi' => $epi], 201);
        }

        return redirect()->route('epi.index')->with('success', 'EPI cadastrado com sucesso!');
    }

    // Mostrar um EPI específico
    public function show($id)
    {
        $epi = Epi::with('funcionario')->find($id);
        
        if (!$epi) {
            return redirect()->route('epi.index')->with('error', 'EPI não encontrado');
        }
        
        return view('epi.show', ['epi' => $epi]);
    }

    // Mostrar formulário de edição
    public function edit($id)
    {
        $epi = Epi::findOrFail($id);
        return view('epi.edit', ['epi' => $epi]);
    }

    // Atualizar EPI
    public function update(Request $request, $id)
    {
        $epi = Epi::find($id);
        
        if (!$epi) {
            return response()->json(['message' => 'EPI não encontrado'], 404);
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

        if ($request->expectsJson()) {
            return response()->json(['message' => 'EPI atualizado com sucesso!', 'epi' => $epi]);
        }

        return redirect()->route('epi.index')->with('success', 'EPI atualizado com sucesso!');
    }

    // Deletar EPI (soft delete)
    public function destroy($id)
    {
        $epi = Epi::find($id);
        
        if (!$epi) {
            return response()->json(['message' => 'EPI não encontrado'], 404);
        }

        $epi->delete();

        return response()->json(['message' => 'EPI deletado com sucesso!']);
    }
}
