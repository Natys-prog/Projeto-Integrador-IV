<?php
// filepath: c:\Users\mrros\source\repos\Projeto-Integrador-IV\laravel-app\app\Http\Controllers\FuncionarioController.php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\Departamento;
use App\Models\Cargo;
use Illuminate\Http\Request;

class FuncionarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Funcionario::with(['departamento', 'cargo']);

        // Filtros opcionais
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('departamento_id') && $request->departamento_id != '') {
            $query->where('departamento_id', $request->departamento_id);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', '%' . $search . '%')
                  ->orWhere('matricula', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Ordenação
        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');
        $query->orderBy($orderBy, $orderDirection);

        // Paginação
        $perPage = $request->get('per_page', 15);
        $funcionarios = $query->paginate($perPage);
        $funcionarios->appends($request->query());

        // Dados auxiliares
        $departamentos = Departamento::ativo()->orderBy('nome')->get();
        $cargos = Cargo::ativo()->orderBy('nome')->get();

        return view('funcionario', [
            'funcionarios' => $funcionarios,
            'departamentos' => $departamentos,
            'cargos' => $cargos,
            'filters' => $request->only(['status', 'departamento_id', 'search', 'order_by', 'order_direction']),
            'serverTime' => date('Y-m-d H:i:s')
        ]);
    }

    public function show($id)
    {
        $funcionario = Funcionario::with(['departamento', 'cargo', 'epis.tipoEpi'])->findOrFail($id);
        return view('funcionarios.show', compact('funcionario'));
    }
}
