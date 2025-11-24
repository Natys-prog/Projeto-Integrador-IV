<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    // Listar todos os Funcionários
    public function index()
    {
        $func = Funcionario::all();
        
        return view('funcionario', [
            'func' => $func,
            'serverTime' => date('Y-m-d H:i:s')
        ]);
    }

    // Listar Funcionários deletados
    public function trashed()
    {
        $func = Funcionario::onlyTrashed()->get();
        
        return view('funcionario.trashed', [
            'func' => $func,
            'serverTime' => date('Y-m-d H:i:s')
        ]);
    }

    // Mostrar um Funcionário específico
    public function show($id)
    {
        $funcionario = Funcionario::find($id);
        
        if (!$funcionario) {
            return redirect()->route('funcionario.index')->with('error', 'Funcionário não encontrado');
        }
        
        return view('funcionario.show', ['funcionario' => $funcionario]);
    }

    // Salvar novo Funcionário
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|unique:funcionarios,cpf|max:14',
            'email' => 'required|email|unique:funcionarios,email',
            'telefone' => 'nullable|string|max:20',
            'data_admissao' => 'required|date',
            'cargo' => 'required|string',
            'departamento' => 'required|string',
            'salario' => 'nullable|numeric|min:0',
            'endereco' => 'nullable|string',
            'cidade' => 'nullable|string',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:9',
        ]);

        Funcionario::create($validated);

        return response()->json(['message' => 'Funcionário cadastrado com sucesso!'], 201);
    }

    // Atualizar Funcionário
    public function update(Request $request, $id)
    {
        $funcionario = Funcionario::find($id);
        
        if (!$funcionario) {
            return response()->json(['message' => 'Funcionário não encontrado'], 404);
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:funcionarios,cpf,' . $id,
            'email' => 'required|email|unique:funcionarios,email,' . $id,
            'telefone' => 'nullable|string|max:20',
            'data_admissao' => 'required|date',
            'cargo' => 'required|string',
            'departamento' => 'required|string',
            'salario' => 'nullable|numeric|min:0',
            'endereco' => 'nullable|string',
            'cidade' => 'nullable|string',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:9',
        ]);

        $funcionario->update($validated);

        return response()->json(['message' => 'Funcionário atualizado com sucesso!', 'funcionario' => $funcionario]);
    }

    // Deletar Funcionário (soft delete)
    public function destroy($id)
    {
        $funcionario = Funcionario::find($id);
        
        if (!$funcionario) {
            return response()->json(['message' => 'Funcionário não encontrado'], 404);
        }

        $funcionario->delete();

        return response()->json(['message' => 'Funcionário deletado com sucesso! Data: ' . $funcionario->deleted_at]);
    }

    // Restaurar Funcionário
    public function restore($id)
    {
        $funcionario = Funcionario::withTrashed()->find($id);
        
        if (!$funcionario) {
            return response()->json(['message' => 'Funcionário não encontrado'], 404);
        }

        $funcionario->restore();

        return response()->json(['message' => 'Funcionário restaurado com sucesso!']);
    }
}
