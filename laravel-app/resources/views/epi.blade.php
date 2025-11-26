@extends('layouts.dashboard')

@section('title', 'Gerenciamento de EPIs')

@section('content')

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h1>🦺 Gerenciamento de EPIs</h1>
    <button onclick="abrirModal()" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; border: none; cursor: pointer; font-weight: 600; font-size: 1rem;">
        ➕ Novo EPI
    </button>
</div>

<!-- Filtros -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div style="padding: 1.5rem;">
        <form method="GET" action="{{ route('epi.index') }}" style="display: grid; grid-template-columns: 1fr 1fr 1fr 2fr auto; gap: 1rem; align-items: end;">
            
            <!-- Filtro por Status -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Status:</label>
                <select name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">Todos</option>
                    <option value="ativo" {{ ($filters['status'] ?? '') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                    <option value="inativo" {{ ($filters['status'] ?? '') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                    <option value="manutencao" {{ ($filters['status'] ?? '') == 'manutencao' ? 'selected' : '' }}>Manutenção</option>
                    <option value="descartado" {{ ($filters['status'] ?? '') == 'descartado' ? 'selected' : '' }}>Descartado</option>
                </select>
            </div>

            <!-- Filtro por Tipo -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Tipo:</label>
                <select name="tipo" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">Todos</option>
                    <option value="capacete" {{ ($filters['tipo'] ?? '') == 'capacete' ? 'selected' : '' }}>Capacete</option>
                    <option value="oculos" {{ ($filters['tipo'] ?? '') == 'oculos' ? 'selected' : '' }}>Óculos</option>
                    <option value="luvas" {{ ($filters['tipo'] ?? '') == 'luvas' ? 'selected' : '' }}>Luvas</option>
                    <option value="botas" {{ ($filters['tipo'] ?? '') == 'botas' ? 'selected' : '' }}>Botas</option>
                    <option value="cinto_seguranca" {{ ($filters['tipo'] ?? '') == 'cinto_seguranca' ? 'selected' : '' }}>Cinto de Segurança</option>
                    <option value="mascara" {{ ($filters['tipo'] ?? '') == 'mascara' ? 'selected' : '' }}>Máscara</option>
                    <option value="protetor_auditivo" {{ ($filters['tipo'] ?? '') == 'protetor_auditivo' ? 'selected' : '' }}>Protetor Auditivo</option>
                    <option value="colete_refletivo" {{ ($filters['tipo'] ?? '') == 'colete_refletivo' ? 'selected' : '' }}>Colete Refletivo</option>
                    <option value="outros" {{ ($filters['tipo'] ?? '') == 'outros' ? 'selected' : '' }}>Outros</option>
                </select>
            </div>

            <!-- Ordenação -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Ordenar por:</label>
                <select name="order_by" style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="created_at" {{ ($filters['order_by'] ?? '') == 'created_at' ? 'selected' : '' }}>Data de Criação</option>
                    <option value="nome" {{ ($filters['order_by'] ?? '') == 'nome' ? 'selected' : '' }}>Nome</option>
                    <option value="tipo" {{ ($filters['order_by'] ?? '') == 'tipo' ? 'selected' : '' }}>Tipo</option>
                    <option value="data_vencimento" {{ ($filters['order_by'] ?? '') == 'data_vencimento' ? 'selected' : '' }}>Data de Vencimento</option>
                </select>
            </div>

            <!-- Busca -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem;">Buscar:</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $filters['search'] ?? '' }}" 
                    placeholder="Nome, código ou fabricante..."
                    style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;"
                >
            </div>

            <!-- Botões -->
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" style="background: #667eea; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem;">
                    🔍 Filtrar
                </button>
                <a href="{{ route('epi.index') }}" style="background: #6c757d; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-size: 0.9rem;">
                    🔄 Limpar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Informações de Resultados -->
<div style="margin-bottom: 1rem; color: #666; font-size: 0.9rem;">
    Mostrando {{ $epis->firstItem() ?? 0 }} a {{ $epis->lastItem() ?? 0 }} de {{ $epis->total() }} EPIs
    @if($filters['search'] ?? false)
        | Busca por: "<strong>{{ $filters['search'] }}</strong>"
    @endif
</div>

<!-- Tabela de EPIs -->
<div class="card">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8f9fa; border-bottom: 2px solid #e0e0e0;">
                <th style="padding: 1rem; text-align: left; font-weight: 600;">Nome</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600;">Tipo</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600;">Código</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600;">Status</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600;">Funcionário</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600;">Vencimento</th>
                <th style="padding: 1rem; text-align: center; font-weight: 600;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($epis as $epi)
            <tr style="border-bottom: 1px solid #ecf0f1; transition: background 0.3s;" 
                onmouseover="this.style.background='#f8f9fa'" 
                onmouseout="this.style.background='transparent'">
                <td style="padding: 1rem;">{{ $epi->nome }}</td>
                <td style="padding: 1rem;">
                    <span style="background: #e3f2fd; color: #1976d2; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem;">
                        {{ ucfirst(str_replace('_', ' ', $epi->tipo)) }}
                    </span>
                </td>
                <td style="padding: 1rem; font-family: monospace;">{{ $epi->codigo }}</td>
                <td style="padding: 1rem;">
                    @php
                        $statusColors = [
                            'ativo' => ['bg' => '#e8f5e8', 'color' => '#2e7d32'],
                            'inativo' => ['bg' => '#ffebee', 'color' => '#c62828'],
                            'manutencao' => ['bg' => '#fff3e0', 'color' => '#ef6c00'],
                            'descartado' => ['bg' => '#f3e5f5', 'color' => '#7b1fa2']
                        ];
                        $style = $statusColors[$epi->status] ?? $statusColors['ativo'];
                    @endphp
                    <span style="background: {{ $style['bg'] }}; color: {{ $style['color'] }}; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem;">
                        {{ ucfirst($epi->status) }}
                    </span>
                </td>
                <td style="padding: 1rem;">
                    {{ $epi->funcionario ? $epi->funcionario->nome : 'Não atribuído' }}
                </td>
                <td style="padding: 1rem;">
                    @if($epi->data_vencimento)
                        @php
                            $diasVencimento = now()->diffInDays($epi->data_vencimento, false);
                            $isVencido = $diasVencimento < 0;
                            $isProximo = $diasVencimento <= 30 && $diasVencimento >= 0;
                        @endphp
                        <span style="color: {{ $isVencido ? '#c62828' : ($isProximo ? '#ef6c00' : '#2e7d32') }};">
                            {{ $epi->data_vencimento->format('d/m/Y') }}
                            @if($isVencido)
                                (Vencido)
                            @elseif($isProximo)
                                ({{ $diasVencimento }} dias)
                            @endif
                        </span>
                    @else
                        <span style="color: #757575;">Sem vencimento</span>
                    @endif
                </td>
                <td style="padding: 1rem; text-align: center;">
                    <div class="dropdown-actions">
                        <button class="dropdown-btn">
                            ⚙️ Ações
                            <span style="font-size: 0.7rem;">▼</span>
                        </button>
                        <div class="dropdown-content">
                            <button onclick="editarEPI({{ $epi->id }})" class="dropdown-item edit">
                                ✏️ Editar EPI
                            </button>
                            <button onclick="deletarEPI({{ $epi->id }})" class="dropdown-item delete">
                                🗑️ Deletar EPI
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="padding: 2rem; text-align: center; color: #7f8c8d;">
                    @if($filters['search'] ?? false)
                        Nenhum EPI encontrado para a busca "<strong>{{ $filters['search'] }}</strong>".
                    @else
                        Nenhum EPI cadastrado. <a href="#" onclick="abrirModal()" style="color: #667eea; text-decoration: none; font-weight: 600;">Clique aqui para criar um.</a>
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginação -->
@if($epis->hasPages())
<div style="margin-top: 2rem; display: flex; justify-content: center;">
    <div style="display: flex; align-items: center; gap: 0.5rem;">
        
        {{-- Link para Primeira Página --}}
        @if($epis->currentPage() > 1)
            <a href="{{ $epis->url(1) }}" style="padding: 0.5rem 1rem; background: #f8f9fa; border: 1px solid #dee2e6; color: #667eea; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">
                ⏮️ Primeira
            </a>
        @endif

        {{-- Link Anterior --}}
        @if($epis->previousPageUrl())
            <a href="{{ $epis->previousPageUrl() }}" style="padding: 0.5rem 1rem; background: #f8f9fa; border: 1px solid #dee2e6; color: #667eea; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">
                ◀️ Anterior
            </a>
        @endif

        {{-- Números das Páginas --}}
        @php
            $start = max(1, $epis->currentPage() - 2);
            $end = min($epis->lastPage(), $epis->currentPage() + 2);
        @endphp

        @for($i = $start; $i <= $end; $i++)
            @if($i == $epis->currentPage())
                <span style="padding: 0.5rem 1rem; background: #667eea; color: white; border: 1px solid #667eea; border-radius: 4px; font-size: 0.9rem; font-weight: 600;">
                    {{ $i }}
                </span>
            @else
                <a href="{{ $epis->url($i) }}" style="padding: 0.5rem 1rem; background: #f8f9fa; border: 1px solid #dee2e6; color: #667eea; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">
                    {{ $i }}
                </a>
            @endif
        @endfor

        {{-- Link Próximo --}}
        @if($epis->nextPageUrl())
            <a href="{{ $epis->nextPageUrl() }}" style="padding: 0.5rem 1rem; background: #f8f9fa; border: 1px solid #dee2e6; color: #667eea; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">
                Próxima ▶️
            </a>
        @endif

        {{-- Link para Última Página --}}
        @if($epis->currentPage() < $epis->lastPage())
            <a href="{{ $epis->url($epis->lastPage()) }}" style="padding: 0.5rem 1rem; background: #f8f9fa; border: 1px solid #dee2e6; color: #667eea; text-decoration: none; border-radius: 4px; font-size: 0.9rem;">
                Última ⏭️
            </a>
        @endif
    </div>
</div>

<!-- Informações da Paginação -->
<div style="text-align: center; margin-top: 1rem; color: #6c757d; font-size: 0.9rem;">
    Página {{ $epis->currentPage() }} de {{ $epis->lastPage() }} 
    ({{ $epis->total() }} EPIs no total)
</div>
@endif

<!-- Incluir o Modal de Cadastro -->
@include('layouts.cadastroepi')

<style>
.dropdown-actions {
    position: relative;
    display: inline-block;
}

.dropdown-btn {
    background: #667eea;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
}

.dropdown-btn:hover {
    background: #5a67d8;
    transform: translateY(-1px);
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background: white;
    min-width: 140px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-radius: 6px;
    z-index: 1000;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.dropdown-actions:hover .dropdown-content {
    display: block;
}

.dropdown-item {
    display: block;
    width: 100%;
    padding: 0.75rem 1rem;
    text-align: left;
    border: none;
    background: white;
    cursor: pointer;
    font-size: 0.875rem;
    transition: background 0.2s ease;
    color: #374151;
}

.dropdown-item:hover {
    background: #f8fafc;
}

.dropdown-item.edit {
    color: #3498db;
}

.dropdown-item.delete {
    color: #e74c3c;
}

.dropdown-item.delete:hover {
    background: #fef2f2;
}
</style>

<script>
    // Função para deletar EPI
    async function deletarEPI(id) {
        if (!confirm('Tem certeza que deseja deletar este EPI?')) {
            return;
        }
        
        try {
            const response = await fetch(`/api/epis/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                alert('EPI deletado com sucesso!');
                location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao deletar EPI');
        }
    }
    
    // Função para editar EPI (usa o modal do cadastroepi.blade.php)
    function editarEPI(id) {
        editarEPI(id); // Chama a função do modal
    }
</script>

@endsection
