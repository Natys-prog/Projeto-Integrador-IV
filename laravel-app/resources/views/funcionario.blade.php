@extends('layouts.dashboard')

@section('title', 'Gerenciamento de Funcionários')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>🦺 Gerenciamento de Funcionarios</h1>
    <button onclick="abrirModal()" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 1rem;">
        ➕ Novo Funcionário
    </button>
</div>

<!-- Tabela de Func -->
<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 1rem; text-align: left; font-weight: 600;">Nome</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600;">CPF</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600;">Admissão</th>
                <th style="padding: 1rem; text-align: center; font-weight: 600;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($func as $func)
            <tr style="border-bottom: 1px solid #ecf0f1; transition: background 0.3s;">
                <td style="padding: 1rem;">{{ $func['nome'] ?? 'N/A' }}</td>
                <td style="padding: 1rem;">{{ $func['cpf'] ?? 'N/A' }}</td>
                <td style="padding: 1rem;">{{ $func['data_admissao'] ?? 0 }}</td>
                <td style="padding: 1rem; text-align: center;">
                    <button style="background: #3498db; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; margin-right: 0.5rem;">
                        ✏️ Editar
                    </button>
                    <button style="background: #e74c3c; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer;">
                        🗑️ Deletar
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 2rem; text-align: center; color: #7f8c8d;">
                    Nenhum Funcionario cadastrado. <a href="#" onclick="abrirModal()" style="color: #667eea; text-decoration: none; font-weight: 600;">Clique aqui para criar um.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Incluir o Modal de Cadastro -->
@include('layouts.cadastroFuncionario')

@endsection