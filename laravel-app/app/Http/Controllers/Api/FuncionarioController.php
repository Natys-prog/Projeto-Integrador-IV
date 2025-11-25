<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FuncionarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $funcionarios = Funcionario::all();
        return response()->json([
            'success' => true,
            'message' => 'Funcionários recuperados com sucesso',
            'data' => $funcionarios
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
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

        $funcionario = Funcionario::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Funcionário cadastrado com sucesso!',
            'data' => $funcionario
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $funcionario = Funcionario::find($id);

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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $funcionario = Funcionario::find($id);

        if (!$funcionario) {
            return response()->json([
                'success' => false,
                'message' => 'Funcionário não encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'cpf' => 'sometimes|required|string|max:14|unique:funcionarios,cpf,' . $id,
            'email' => 'sometimes|required|email|unique:funcionarios,email,' . $id,
            'telefone' => 'nullable|string|max:20',
            'data_admissao' => 'sometimes|required|date',
            'cargo' => 'sometimes|required|string',
            'departamento' => 'sometimes|required|string',
            'salario' => 'nullable|numeric|min:0',
            'endereco' => 'nullable|string',
            'cidade' => 'nullable|string',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:9',
        ]);

        $funcionario->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Funcionário atualizado com sucesso!',
            'data' => $funcionario
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $funcionario = Funcionario::find($id);

        if (!$funcionario) {
            return response()->json([
                'success' => false,
                'message' => 'Funcionário não encontrado'
            ], 404);
        }

        $funcionario->delete();

        return response()->json([
            'success' => true,
            'message' => 'Funcionário deletado com sucesso!',
            'deleted_at' => $funcionario->deleted_at
        ]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id): JsonResponse
    {
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
            'data' => $funcionario
        ]);
    }
}
