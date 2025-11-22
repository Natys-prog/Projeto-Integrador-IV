<?php

namespace App\Http\Controllers;

use App\Models\Epi;
use Illuminate\Http\Request;

class EpiController extends Controller
{
    // Listar todos os EPIs (apenas os não deletados)
    public function index()
    {
        $epis = Epi::all(); // Busca apenas EPIs com deleted_at = NULL
        
        return view('epi', [
            'epis' => $epis,
            'serverTime' => date('Y-m-d H:i:s')
        ]);
    }

    // Listar EPIs deletados (para recuperação/auditoria)
    public function trashed()
    {
        $epis = Epi::onlyTrashed()->get(); // Busca apenas deletados
        
        return view('epi.trashed', [
            'epis' => $epis,
            'serverTime' => date('Y-m-d H:i:s')
        ]);
    }

    // Mostrar um EPI específico
    public function show($id)
    {
        $epi = Epi::find($id); // Busca por ID
        
        if (!$epi) {
            return redirect()->route('epi.index')->with('error', 'EPI não encontrado');
        }
        
        return view('epi.show', ['epi' => $epi]);
    }

    // Salvar novo EPI
    public function store(Request $request)
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

        Epi::create($validated);

        return response()->json(['message' => 'EPI cadastrado com sucesso!'], 201);
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

        $epi->update($validated);

        return response()->json(['message' => 'EPI atualizado com sucesso!', 'epi' => $epi]);
    }

    // Deletar EPI (soft delete - registra data/hora)
    public function destroy($id)
    {
        $epi = Epi::find($id);
        
        if (!$epi) {
            return response()->json(['message' => 'EPI não encontrado'], 404);
        }

        $epi->delete(); // Apenas registra a data em deleted_at

        return response()->json(['message' => 'EPI deletado com sucesso! Data: ' . $epi->deleted_at]);
    }

    // Restaurar EPI deletado
    public function restore($id)
    {
        $epi = Epi::withTrashed()->find($id);
        
        if (!$epi) {
            return response()->json(['message' => 'EPI não encontrado'], 404);
        }

        $epi->restore(); // Remove o deleted_at (volta a NULL)

        return response()->json(['message' => 'EPI restaurado com sucesso!']);
    }

    // Deletar permanentemente (hard delete)
    public function forceDelete($id)
    {
        $epi = Epi::withTrashed()->find($id);
        
        if (!$epi) {
            return response()->json(['message' => 'EPI não encontrado'], 404);
        }

        $epi->forceDelete(); // Remove do banco de verdade

        return response()->json(['message' => 'EPI deletado permanentemente!']);
    }
}
