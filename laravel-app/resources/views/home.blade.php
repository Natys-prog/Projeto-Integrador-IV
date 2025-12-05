@auth
    @extends('layouts.dashboard')
    
    @section('title', 'Dashboard')
    
    @section('content')
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="card">
            <div class="card-header">
                <div class="card-icon epi-icon">
                    🦺
                </div>
                <div>
                    <div class="card-title">Total EPIs</div>
                </div>
            </div>
            <div class="card-value">{{ $totalEpis ?? 0 }}</div>
            <div class="card-description">Equipamentos cadastrados no sistema</div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-icon funcionario-icon">
                    👥
                </div>
                <div>
                    <div class="card-title">Funcionários</div>
                </div>
            </div>
            <div class="card-value">{{ $totalFuncionarios ?? 0 }}</div>
            <div class="card-description">Funcionários ativos no sistema</div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-icon alert-icon">
                    ⚠️
                </div>
                <div>
                    <div class="card-title">Alertas</div>
                </div>
            </div>
            <div class="card-value">{{ $alertasAtivos ?? 0 }}</div>
            <div class="card-description">EPIs próximos ao vencimento</div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="card-icon maintenance-icon">
                    🔧
                </div>
                <div>
                    <div class="card-title">Manutenções</div>
                </div>
            </div>
            <div class="card-value">{{ $manutencoesPendentes ?? 0 }}</div>
            <div class="card-description">Manutenções pendentes</div>
        </div>
    </div>
    
    <!-- Main Dashboard Content -->
    <div class="dashboard-grid">
        <!-- EPIs Information -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon epi-icon">
                    🦺
                </div>
                <div>
                    <div class="card-title">Gestão de EPIs</div>
                </div>
            </div>
            <p style="margin-bottom: 1rem; color: #7f8c8d;">
                Gerencie todos os Equipamentos de Proteção Individual da empresa.
            </p>
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 0.5rem 0; border-bottom: 1px solid #ecf0f1;">
                    <strong>Capacetes:</strong> {{ $epis['capacetes'] ?? 0 }} unidades
                </li>
                <li style="padding: 0.5rem 0; border-bottom: 1px solid #ecf0f1;">
                    <strong>Óculos de Proteção:</strong> {{ $epis['oculos'] ?? 0 }} unidades
                </li>
                <li style="padding: 0.5rem 0; border-bottom: 1px solid #ecf0f1;">
                    <strong>Luvas:</strong> {{ $epis['luvas'] ?? 0 }} pares
                </li>
                <li style="padding: 0.5rem 0; border-bottom: 1px solid #ecf0f1;">
                    <strong>Botas de Segurança:</strong> {{ $epis['botas'] ?? 0 }} pares
                </li>
                <li style="padding: 0.5rem 0;">
                    <strong>Cintos de Segurança:</strong> {{ $epis['cintos'] ?? 0 }} unidades
                </li>
            </ul>
            <div style="margin-top: 1.5rem;">
                <a href="epi" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; display: inline-block;">
                    Ver Todos os EPIs
                </a>
            </div>
        </div>
        
        <!-- Funcionários Information -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon funcionario-icon">
                    👥
                </div>
                <div>
                    <div class="card-title">Gestão de Funcionários</div>
                </div>
            </div>
            <p style="margin-bottom: 1rem; color: #7f8c8d;">
                Controle de funcionários e atribuição de EPIs.
            </p>
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 0.5rem 0; border-bottom: 1px solid #ecf0f1;">
                    <strong>Departamento Produção:</strong> {{ $funcionarios['producao'] ?? 0 }} funcionários
                </li>
                <li style="padding: 0.5rem 0; border-bottom: 1px solid #ecf0f1;">
                    <strong>Departamento Manutenção:</strong> {{ $funcionarios['manutencao'] ?? 0 }} funcionários
                </li>
                <li style="padding: 0.5rem 0; border-bottom: 1px solid #ecf0f1;">
                    <strong>Departamento Logística:</strong> {{ $funcionarios['logistica'] ?? 0 }} funcionários
                </li>
                <li style="padding: 0.5rem 0; border-bottom: 1px solid #ecf0f1;">
                    <strong>Funcionários com EPIs Vencidos:</strong> {{ $funcionarios['episVencidos'] ?? 0 }}
                </li>
                <li style="padding: 0.5rem 0;">
                    <strong>Novos Funcionários (Este Mês):</strong> {{ $funcionarios['novos'] ?? 0 }}
                </li>
            </ul>
            <div style="margin-top: 1.5rem;">
                <a href="funcionarios" style="background: #f5576c; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; display: inline-block;">
                    Ver Funcionários
                </a>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card" style="grid-column: span 2;">
            <div class="card-header">
                <div class="card-icon" style="background: linear-gradient(135deg, #a8edea, #fed6e3); color: #2c3e50;">
                    📋
                </div>
                <div>
                    <div class="card-title">Atividades Recentes</div>
                </div>
            </div>
            <div style="max-height: 300px; overflow-y: auto;">
                @forelse($atividadesRecentes ?? [] as $atividade)
                <div style="padding: 1rem 0; border-bottom: 1px solid #ecf0f1; display: flex; align-items: center;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #667eea; color: white; display: flex; align-items: center; justify-content: center; margin-right: 1rem; font-size: 0.8rem;">
                        {{ substr($atividade['tipo'], 0, 2) }}
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: #2c3e50;">{{ $atividade['descricao'] }}</div>
                        <div style="font-size: 0.8rem; color: #7f8c8d;">{{ $atividade['data'] }}</div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 2rem; color: #7f8c8d;">
                    <p>Nenhuma atividade recente encontrada.</p>
                    <p><small>As atividades aparecerão aqui conforme você usar o sistema.</small></p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- System Info -->
    <div class="card" style="margin-top: 2rem;">
        <div style="text-align: center; color: #7f8c8d;">
            <p><strong>Sistema de Gestão EPI</strong> - Versão 1.0</p>
            <p>Horário do servidor: {{ $serverTime }}</p>
            <p style="margin-top: 1rem;">
                <a href="{{ route('init-db') }}" style="color: #667eea; text-decoration: none;">
                    🔄 Inicializar Base de Dados
                </a>
            </p>
        </div>
    </div>
    @endsection

@else
    @extends('layouts.app')
    
    @section('title', 'Home')
    
    @section('content')
    <section class="card">
        <h2>Bem-vindo ao Sistema de Gestão EPI</h2>
        <p>Para acessar o dashboard, faça login no sistema.</p>
        <p>Este sistema permite gerenciar Equipamentos de Proteção Individual (EPIs) e funcionários.</p>
        <div style="margin-top: 2rem;">
            <a href="{{ route('login') }}" style="background: #0b72b9; color: white; padding: 1rem 2rem; border-radius: 6px; text-decoration: none; display: inline-block;">
                Fazer Login
            </a>
        </div>
        <p style="margin-top: 1rem; color: #666;">Server time: {{ $serverTime }}</p>
    </section>
    @endsection
@endauth