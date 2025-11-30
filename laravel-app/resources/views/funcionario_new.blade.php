@extends('layouts.dashboard')

@section('title', 'Gerenciamento de Funcionários')

@section('content')
<!-- Page Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="color: #2c3e50; font-size: 1.8rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.75rem;">
            👥 Gerenciamento de Funcionários
        </h1>
        <p style="color: #7f8c8d; margin: 0.5rem 0 0 0;">Controle completo dos funcionários e suas informações</p>
    </div>
    <div style="display: flex; gap: 1rem;">
        <button class="btn-primary" onclick="openModal('create')" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
            ➕ Novo Funcionário
        </button>
        <button onclick="refreshData()" style="background: #34495e; color: white; border: none; padding: 0.75rem 1rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
            🔄 Atualizar
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="text-align: center; padding: 1.5rem;">
        <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">📊</div>
        <div style="font-size: 2rem; font-weight: bold; color: #2c3e50;" id="total-funcionarios">0</div>
        <div style="color: #7f8c8d; font-size: 0.9rem;">Total de Funcionários</div>
    </div>
    <div class="card" style="text-align: center; padding: 1.5rem;">
        <div style="background: linear-gradient(135deg, #48bb78, #38a169); color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">✅</div>
        <div style="font-size: 2rem; font-weight: bold; color: #2c3e50;" id="active-funcionarios">0</div>
        <div style="color: #7f8c8d; font-size: 0.9rem;">Ativos</div>
    </div>
    <div class="card" style="text-align: center; padding: 1.5rem;">
        <div style="background: linear-gradient(135deg, #ed8936, #dd6b20); color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">📋</div>
        <div style="font-size: 2rem; font-weight: bold; color: #2c3e50;" id="afastado-funcionarios">0</div>
        <div style="color: #7f8c8d; font-size: 0.9rem;">Afastados</div>
    </div>
    <div class="card" style="text-align: center; padding: 1.5rem;">
        <div style="background: linear-gradient(135deg, #f56565, #e53e3e); color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">❌</div>
        <div style="font-size: 2rem; font-weight: bold; color: #2c3e50;" id="inactive-funcionarios">0</div>
        <div style="color: #7f8c8d; font-size: 0.9rem;">Inativos</div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #ecf0f1;">
        <h3 style="color: #2c3e50; margin: 0; font-weight: 600;">Filtros de Pesquisa</h3>
        <button onclick="clearFilters()" style="background: #e2e8f0; color: #4a5568; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
            🔄 Limpar Filtros
        </button>
    </div>
    
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 1rem; align-items: end;">
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Buscar</label>
            <div style="display: flex;">
                <input type="text" id="search-input" placeholder="Nome, matrícula, email ou CPF..." style="flex: 1; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px 0 0 6px; font-size: 0.875rem;" />
                <button onclick="applyFilters()" style="background: #667eea; color: white; border: 1px solid #667eea; border-radius: 0 6px 6px 0; padding: 0.75rem 1rem; cursor: pointer;">
                    🔍
                </button>
            </div>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Status</label>
            <select id="status-filter" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;">
                <option value="">Todos os Status</option>
                <option value="ativo">Ativo</option>
                <option value="inativo">Inativo</option>
                <option value="afastado">Afastado</option>
                <option value="demitido">Demitido</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Departamento</label>
            <select id="departamento-filter" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;">
                <option value="">Todos os Departamentos</option>
                <!-- Populated dynamically -->
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Ordenar por</label>
            <select id="order-filter" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;">
                <option value="created_at">Data de Criação</option>
                <option value="nome">Nome</option>
                <option value="data_admissao">Data de Admissão</option>
                <option value="matricula">Matrícula</option>
            </select>
        </div>
    </div>
</div>

<!-- Funcionários Table -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #ecf0f1;">
        <h3 style="color: #2c3e50; margin: 0; font-weight: 600;">Lista de Funcionários</h3>
        <div id="results-info" style="color: #7f8c8d; font-size: 0.875rem;">
            Carregando...
        </div>
    </div>
    
    <!-- Loading State -->
    <div id="loading-container" style="display: none; text-align: center; padding: 3rem; color: #7f8c8d;">
        <div style="font-size: 3rem; margin-bottom: 1rem;">⏳</div>
        <div style="font-size: 1.1rem;">Carregando funcionários...</div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" style="display: none; text-align: center; padding: 3rem; color: #7f8c8d;">
        <div style="font-size: 4rem; margin-bottom: 1rem;">👥</div>
        <div style="font-size: 1.2rem; font-weight: 600; margin-bottom: 0.5rem;">Nenhum funcionário encontrado</div>
        <div>Tente ajustar os filtros ou adicione um novo funcionário</div>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table id="funcionarios-table" style="width: 100%; border-collapse: collapse; display: none;">
            <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #495057;">Funcionário</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #495057;">Matrícula</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #495057;">Departamento</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #495057;">Cargo</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #495057;">Status</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #495057;">EPIs</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #495057;">Ações</th>
                </tr>
            </thead>
            <tbody id="funcionarios-tbody">
                <!-- Populated dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal para Funcionário -->
<div id="funcionario-modal" class="modal" onclick="handleModalClick(event)">
    <div class="modal-content" style="max-width: 800px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h2 id="modal-title">Novo Funcionário</h2>
            <button class="modal-close" onclick="closeModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #7f8c8d;">&times;</button>
        </div>
        
        <form id="funcionario-form">
            <input type="hidden" id="funcionario-id">
            
            <!-- Informações Pessoais -->
            <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
                <h3 style="color: #2d3748; margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600;">👤 Informações Pessoais</h3>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Nome Completo *</label>
                        <input type="text" id="nome" name="nome" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;" placeholder="Digite o nome completo">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">CPF *</label>
                        <input type="text" id="cpf" name="cpf" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;" placeholder="000.000.000-00" maxlength="14">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">RG</label>
                        <input type="text" id="rg" name="rg" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;" placeholder="00.000.000-0">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Data de Nascimento</label>
                        <input type="date" id="data_nascimento" name="data_nascimento" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Gênero</label>
                        <select id="genero" name="genero" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;">
                            <option value="">Selecione</option>
                            <option value="masculino">Masculino</option>
                            <option value="feminino">Feminino</option>
                            <option value="outro">Outro</option>
                        </select>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">E-mail *</label>
                        <input type="email" id="email" name="email" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;" placeholder="funcionario@empresa.com">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Telefone</label>
                        <input type="text" id="telefone" name="telefone" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;" placeholder="(11) 99999-9999" maxlength="15">
                    </div>
                </div>
            </div>
            
            <!-- Informações Profissionais -->
            <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem;">
                <h3 style="color: #2d3748; margin: 0 0 1rem 0; font-size: 1rem; font-weight: 600;">💼 Informações Profissionais</h3>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Matrícula *</label>
                        <input type="text" id="matricula" name="matricula" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;" placeholder="W001">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Status *</label>
                        <select id="status" name="status" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;">
                            <option value="ativo">Ativo</option>
                            <option value="inativo">Inativo</option>
                            <option value="afastado">Afastado</option>
                            <option value="demitido">Demitido</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Data de Admissão</label>
                        <input type="date" id="data_admissao" name="data_admissao" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;">
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Departamento</label>
                        <select id="departamento_id" name="departamento_id" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;">
                            <option value="">Selecione um departamento</option>
                            <!-- Populated dynamically -->
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Cargo</label>
                        <select id="cargo_id" name="cargo_id" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;">
                            <option value="">Selecione um cargo</option>
                            <!-- Populated dynamically -->
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Observações -->
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">📝 Observações</label>
                <textarea id="observacoes" name="observacoes" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem; resize: vertical;" placeholder="Observações adicionais sobre o funcionário..."></textarea>
            </div>
            
            <!-- Buttons -->
            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                <button type="button" onclick="closeModal()" style="background: #e2e8f0; color: #4a5568; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    Cancelar
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    Salvar Funcionário
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="delete-modal" class="modal" onclick="handleDeleteModalClick(event)">
    <div class="modal-content" style="max-width: 400px;">
        <div style="text-align: center; padding: 2rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🗑️</div>
            <h3 style="color: #2d3748; margin: 0 0 1rem 0;">Confirmar Exclusão</h3>
            <p style="color: #718096; margin-bottom: 2rem;">
                Tem certeza que deseja excluir o funcionário <strong id="delete-funcionario-name"></strong>?
                <br><br>
                <span style="color: #e53e3e; font-size: 0.875rem;">Esta ação não pode ser desfeita.</span>
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <button onclick="closeDeleteModal()" style="background: #e2e8f0; color: #4a5568; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    Cancelar
                </button>
                <button onclick="confirmDelete()" style="background: #e53e3e; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; cursor: pointer; font-weight: 600;">
                    Sim, Excluir
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Card styles */
.card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 1.5rem 0 1.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 1rem;
}

.modal-header h2 {
    color: #2d3748;
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

/* Status badges */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.ativo { background: #d1fae5; color: #065f46; }
.status-badge.inativo { background: #fee2e2; color: #991b1b; }
.status-badge.afastado { background: #fef3c7; color: #92400e; }
.status-badge.demitido { background: #f3f4f6; color: #374151; }

/* Action buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-action {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-edit {
    background: #dbeafe;
    color: #1e40af;
}

.btn-edit:hover {
    background: #bfdbfe;
}

.btn-delete {
    background: #fee2e2;
    color: #dc2626;
}

.btn-delete:hover {
    background: #fecaca;
}

/* Table styles */
table tr:hover {
    background-color: #f8f9fa;
}

table td {
    border-bottom: 1px solid #e2e8f0;
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-spinner {
    animation: spin 2s linear infinite;
}

/* Responsive design */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 1rem;
    }
    
    .card {
        padding: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .btn-action {
        width: 100%;
        justify-content: center;
    }
    
    table {
        font-size: 0.875rem;
    }
    
    th, td {
        padding: 0.5rem !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Global variables
let currentFuncionarioId = null;
let deleteFuncionarioId = null;
let funcionariosData = [];
let departamentos = [];
let cargos = [];

// API Configuration
const API_BASE = '/api';

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

async function initializeApp() {
    console.log('🚀 Inicializando aplicação de funcionários...');
    
    showLoading();
    setupEventListeners();
    
    try {
        await Promise.all([
            loadFuncionarios(),
            loadDepartamentos(),
            loadCargos()
        ]);
        
        renderFuncionariosTable();
        updateStatistics();
        populateDepartamentosFilter();
        populateModalSelects();
        
    } catch (error) {
        console.error('❌ Erro ao inicializar:', error);
        showNotification('Erro ao carregar dados iniciais', 'error');
    } finally {
        hideLoading();
    }
}

function setupEventListeners() {
    // Search input com debounce
    let searchTimeout;
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 300);
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    }

    // Filtros
    const statusFilter = document.getElementById('status-filter');
    if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
    }
    
    const departamentoFilter = document.getElementById('departamento-filter');
    if (departamentoFilter) {
        departamentoFilter.addEventListener('change', applyFilters);
    }
    
    const orderFilter = document.getElementById('order-filter');
    if (orderFilter) {
        orderFilter.addEventListener('change', applyFilters);
    }

    // Form submission
    const funcionarioForm = document.getElementById('funcionario-form');
    if (funcionarioForm) {
        funcionarioForm.addEventListener('submit', handleFormSubmit);
    }

    // CPF formatting
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            formatCPF(e.target);
        });
    }

    // Phone formatting
    const telefoneInput = document.getElementById('telefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            formatPhone(e.target);
        });
    }
}

// API Functions
async function apiRequest(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        }
    };

    const config = { ...defaultOptions, ...options };
    config.headers = { ...defaultOptions.headers, ...options.headers };

    try {
        const response = await fetch(url, config);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('❌ API Request Error:', error);
        throw error;
    }
}

async function loadFuncionarios() {
    try {
        console.log('👥 Carregando funcionários...');
        const response = await apiRequest(`${API_BASE}/funcionarios`);
        funcionariosData = response;
        console.log('✅ Funcionários carregados:', funcionariosData.length);
    } catch (error) {
        console.error('❌ Erro ao carregar funcionários:', error);
        funcionariosData = [];
    }
}

async function loadDepartamentos() {
    try {
        console.log('🏢 Carregando departamentos...');
        const response = await apiRequest(`${API_BASE}/departamentos`);
        departamentos = response;
        console.log('✅ Departamentos carregados:', departamentos.length);
    } catch (error) {
        console.error('❌ Erro ao carregar departamentos:', error);
        departamentos = [];
    }
}

async function loadCargos() {
    try {
        console.log('💼 Carregando cargos...');
        const response = await apiRequest(`${API_BASE}/cargos`);
        cargos = response;
        console.log('✅ Cargos carregados:', cargos.length);
    } catch (error) {
        console.error('❌ Erro ao carregar cargos:', error);
        cargos = [];
    }
}

// UI Functions
function showLoading() {
    const loadingContainer = document.getElementById('loading-container');
    const funcionariosTable = document.getElementById('funcionarios-table');
    const emptyState = document.getElementById('empty-state');
    
    if (loadingContainer) loadingContainer.style.display = 'block';
    if (funcionariosTable) funcionariosTable.style.display = 'none';
    if (emptyState) emptyState.style.display = 'none';
}

function hideLoading() {
    const loadingContainer = document.getElementById('loading-container');
    if (loadingContainer) loadingContainer.style.display = 'none';
}

function showEmptyState() {
    const emptyState = document.getElementById('empty-state');
    const funcionariosTable = document.getElementById('funcionarios-table');
    
    if (emptyState) emptyState.style.display = 'block';
    if (funcionariosTable) funcionariosTable.style.display = 'none';
}

function renderFuncionariosTable() {
    const tbody = document.getElementById('funcionarios-tbody');
    
    if (!tbody) return;

    if (funcionariosData.length === 0) {
        showEmptyState();
        return;
    }

    tbody.innerHTML = '';
    const funcionariosTable = document.getElementById('funcionarios-table');
    const emptyState = document.getElementById('empty-state');
    
    if (funcionariosTable) funcionariosTable.style.display = 'table';
    if (emptyState) emptyState.style.display = 'none';

    funcionariosData.forEach(funcionario => {
        const row = createFuncionarioRow(funcionario);
        tbody.appendChild(row);
    });
    
    updateResultsInfo();
}

function createFuncionarioRow(funcionario) {
    const row = document.createElement('tr');
    
    const episCount = funcionario.epis ? funcionario.epis.length : 0;
    
    row.innerHTML = `
        <td style="padding: 1rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.9rem;">
                    ${funcionario.nome.charAt(0).toUpperCase()}
                </div>
                <div>
                    <div style="font-weight: 600; color: #2c3e50;">${funcionario.nome}</div>
                    <div style="font-size: 0.85rem; color: #7f8c8d;">${funcionario.email}</div>
                    ${funcionario.telefone ? `<div style="font-size: 0.8rem; color: #95a5a6;">📞 ${funcionario.telefone}</div>` : ''}
                </div>
            </div>
        </td>
        <td style="padding: 1rem; text-align: center;">
            <span style="font-weight: 600; color: #34495e;">
                ${funcionario.matricula || '-'}
            </span>
        </td>
        <td style="padding: 1rem; text-align: center;">
            ${funcionario.departamento 
                ? `<span style="background: ${funcionario.departamento.cor || '#667eea'}20; color: ${funcionario.departamento.cor || '#667eea'}; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                     ${funcionario.departamento.nome}
                   </span>`
                : '<span style="color: #95a5a6;">-</span>'
            }
        </td>
        <td style="padding: 1rem; text-align: center;">
            ${funcionario.cargo 
                ? `<div style="font-weight: 500; color: #2c3e50;">${funcionario.cargo.nome}</div>
                   ${funcionario.cargo.nivel ? `<div style="font-size: 0.75rem; color: #95a5a6; text-transform: uppercase;">${funcionario.cargo.nivel}</div>` : ''}`
                : '<span style="color: #95a5a6;">-</span>'
            }
        </td>
        <td style="padding: 1rem; text-align: center;">
            <span class="status-badge ${funcionario.status}">
                ${getStatusIcon(funcionario.status)} ${funcionario.status}
            </span>
        </td>
        <td style="padding: 1rem; text-align: center;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                <span style="font-weight: 600; color: #2c3e50;">${episCount}</span>
                ${episCount > 0 
                    ? '<span style="color: #27ae60;">🛡️</span>'
                    : '<span style="color: #95a5a6;">➖</span>'
                }
            </div>
        </td>
        <td style="padding: 1rem; text-align: center;">
            <div class="action-buttons">
                <button onclick="editFuncionario(${funcionario.id})" class="btn-action btn-edit">
                    <span>✏️</span>
                    <span>Editar</span>
                </button>
                <button onclick="deleteFuncionario(${funcionario.id}, '${funcionario.nome.replace(/'/g, "\\'")}', ${episCount})" class="btn-action btn-delete">
                    <span>🗑️</span>
                    <span>Deletar</span>
                </button>
            </div>
        </td>
    `;

    return row;
}

function getStatusIcon(status) {
    const icons = {
        'ativo': '✅',
        'inativo': '❌',
        'afastado': '📋',
        'demitido': '🚫'
    };
    return icons[status] || '❓';
}

function updateStatistics() {
    const stats = {
        total: funcionariosData.length,
        ativo: funcionariosData.filter(f => f.status === 'ativo').length,
        inativo: funcionariosData.filter(f => f.status === 'inativo').length,
        afastado: funcionariosData.filter(f => f.status === 'afastado').length,
        demitido: funcionariosData.filter(f => f.status === 'demitido').length
    };

    const totalElement = document.getElementById('total-funcionarios');
    const activeElement = document.getElementById('active-funcionarios');
    const afastadoElement = document.getElementById('afastado-funcionarios');
    const inactiveElement = document.getElementById('inactive-funcionarios');
    
    if (totalElement) totalElement.textContent = stats.total;
    if (activeElement) activeElement.textContent = stats.ativo;
    if (afastadoElement) afastadoElement.textContent = stats.afastado;
    if (inactiveElement) inactiveElement.textContent = stats.inativo + stats.demitido;
}

function updateResultsInfo() {
    const total = funcionariosData.length;
    const info = document.getElementById('results-info');
    
    if (info) {
        if (total === 0) {
            info.textContent = 'Nenhum funcionário encontrado';
        } else if (total === 1) {
            info.textContent = '1 funcionário encontrado';
        } else {
            info.textContent = `${total} funcionários encontrados`;
        }
    }
}

function populateDepartamentosFilter() {
    const select = document.getElementById('departamento-filter');
    if (!select) return;
    
    const currentValue = select.value;
    
    // Limpar opções atuais, mas manter "Todos"
    select.innerHTML = '<option value="">Todos os Departamentos</option>';
    
    departamentos.forEach(dept => {
        const option = document.createElement('option');
        option.value = dept.id;
        option.textContent = dept.nome;
        if (dept.cor) option.style.color = dept.cor;
        select.appendChild(option);
    });
    
    // Restaurar valor anterior se existir
    if (currentValue) {
        select.value = currentValue;
    }
}

function populateModalSelects() {
    // Populate departamentos
    const deptSelect = document.getElementById('departamento_id');
    if (deptSelect) {
        deptSelect.innerHTML = '<option value="">Selecione um departamento</option>';
        departamentos.forEach(dept => {
            const option = document.createElement('option');
            option.value = dept.id;
            option.textContent = dept.nome;
            deptSelect.appendChild(option);
        });
    }
    
    // Populate cargos
    const cargoSelect = document.getElementById('cargo_id');
    if (cargoSelect) {
        cargoSelect.innerHTML = '<option value="">Selecione um cargo</option>';
        cargos.forEach(cargo => {
            const option = document.createElement('option');
            option.value = cargo.id;
            option.textContent = cargo.nome;
            cargoSelect.appendChild(option);
        });
    }
}

// Filter Functions
function getFilters() {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const departamentoFilter = document.getElementById('departamento-filter');
    const orderFilter = document.getElementById('order-filter');
    
    return {
        search: searchInput ? searchInput.value.trim() : '',
        status: statusFilter ? statusFilter.value : '',
        departamento_id: departamentoFilter ? departamentoFilter.value : '',
        order_by: orderFilter ? orderFilter.value : 'created_at'
    };
}

function clearFilters() {
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const departamentoFilter = document.getElementById('departamento-filter');
    const orderFilter = document.getElementById('order-filter');
    
    if (searchInput) searchInput.value = '';
    if (statusFilter) statusFilter.value = '';
    if (departamentoFilter) departamentoFilter.value = '';
    if (orderFilter) orderFilter.value = 'created_at';
    
    applyFilters();
}

async function applyFilters() {
    showLoading();
    
    try {
        const filters = getFilters();
        const queryParams = new URLSearchParams();
        
        Object.entries(filters).forEach(([key, value]) => {
            if (value) {
                queryParams.append(key, value);
            }
        });
        
        const url = `${API_BASE}/funcionarios?${queryParams.toString()}`;
        funcionariosData = await apiRequest(url);
        
        renderFuncionariosTable();
        updateStatistics();
        
    } catch (error) {
        console.error('❌ Erro ao aplicar filtros:', error);
        showNotification('Erro ao aplicar filtros', 'error');
    } finally {
        hideLoading();
    }
}

async function refreshData() {
    showNotification('Atualizando dados...', 'info');
    await initializeApp();
    showNotification('Dados atualizados com sucesso!', 'success');
}

// Modal Functions
function openModal(mode, id = null) {
    currentFuncionarioId = id;
    
    const modal = document.getElementById('funcionario-modal');
    const title = document.getElementById('modal-title');
    const form = document.getElementById('funcionario-form');
    
    if (mode === 'create') {
        if (title) title.textContent = 'Novo Funcionário';
        if (form) form.reset();
        document.getElementById('funcionario-id').value = '';
    } else if (mode === 'edit' && id) {
        if (title) title.textContent = 'Editar Funcionário';
        loadFuncionarioData(id);
    }
    
    if (modal) modal.classList.add('active');
}

function closeModal() {
    const modal = document.getElementById('funcionario-modal');
    if (modal) modal.classList.remove('active');
    currentFuncionarioId = null;
}

function handleModalClick(event) {
    if (event.target.id === 'funcionario-modal') {
        closeModal();
    }
}

async function loadFuncionarioData(id) {
    try {
        const funcionario = funcionariosData.find(f => f.id == id);
        if (!funcionario) {
            throw new Error('Funcionário não encontrado');
        }

        // Populate form fields
        const fields = ['nome', 'cpf', 'rg', 'email', 'telefone', 'matricula', 'status', 'genero', 'departamento_id', 'cargo_id', 'observacoes'];
        
        fields.forEach(field => {
            const element = document.getElementById(field);
            if (element && funcionario[field] !== undefined) {
                element.value = funcionario[field] || '';
            }
        });

        // Handle date fields
        if (funcionario.data_nascimento) {
            const nascimento = document.getElementById('data_nascimento');
            if (nascimento) nascimento.value = funcionario.data_nascimento;
        }

        if (funcionario.data_admissao) {
            const admissao = document.getElementById('data_admissao');
            if (admissao) admissao.value = funcionario.data_admissao;
        }

        document.getElementById('funcionario-id').value = funcionario.id;

    } catch (error) {
        console.error('❌ Erro ao carregar dados do funcionário:', error);
        showNotification('Erro ao carregar dados do funcionário', 'error');
        closeModal();
    }
}

// Form Functions
async function handleFormSubmit(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // Remove empty values
    Object.keys(data).forEach(key => {
        if (data[key] === '') {
            delete data[key];
        }
    });

    const isEdit = currentFuncionarioId !== null;
    const url = isEdit 
        ? `${API_BASE}/funcionarios/${currentFuncionarioId}`
        : `${API_BASE}/funcionarios`;
    
    const method = isEdit ? 'PUT' : 'POST';

    try {
        await apiRequest(url, {
            method: method,
            body: JSON.stringify(data)
        });

        showNotification(
            isEdit ? 'Funcionário atualizado com sucesso!' : 'Funcionário criado com sucesso!', 
            'success'
        );
        
        closeModal();
        await refreshData();

    } catch (error) {
        console.error('❌ Erro ao salvar funcionário:', error);
        showNotification('Erro ao salvar funcionário', 'error');
    }
}

// CRUD Functions
function editFuncionario(id) {
    openModal('edit', id);
}

function deleteFuncionario(id, nome, episCount) {
    if (episCount > 0) {
        showNotification(`Não é possível excluir "${nome}" pois possui ${episCount} EPI(s) atribuído(s)`, 'warning');
        return;
    }
    
    deleteFuncionarioId = id;
    const nameElement = document.getElementById('delete-funcionario-name');
    if (nameElement) nameElement.textContent = nome;
    
    const deleteModal = document.getElementById('delete-modal');
    if (deleteModal) deleteModal.classList.add('active');
}

function closeDeleteModal() {
    const deleteModal = document.getElementById('delete-modal');
    if (deleteModal) deleteModal.classList.remove('active');
    deleteFuncionarioId = null;
}

function handleDeleteModalClick(event) {
    if (event.target.id === 'delete-modal') {
        closeDeleteModal();
    }
}

async function confirmDelete() {
    if (!deleteFuncionarioId) return;

    try {
        await apiRequest(`${API_BASE}/funcionarios/${deleteFuncionarioId}`, {
            method: 'DELETE'
        });

        showNotification('Funcionário excluído com sucesso!', 'success');
        closeDeleteModal();
        await refreshData();

    } catch (error) {
        console.error('❌ Erro ao excluir funcionário:', error);
        showNotification('Erro ao excluir funcionário', 'error');
    }
}

// Utility Functions
function formatCPF(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    }
    
    input.value = value;
}

function formatPhone(input) {
    let value = input.value.replace(/\D/g, '');
    
    if (value.length <= 11) {
        if (value.length <= 10) {
            // Telefone fixo: (11) 1234-5678
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            // Celular: (11) 99999-9999
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
    }
    
    input.value = value;
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        max-width: 400px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;

    // Set color based on type
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };

    notification.style.background = colors[type] || colors.info;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}
</script>
@endpush