@extends('layouts.dashboard')

@section('title', 'Gerenciamento de EPIs')

@section('content')
<!-- Page Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div>
        <h1 style="color: #2c3e50; font-size: 1.8rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.75rem;">
            🦺 Gerenciamento de EPIs
        </h1>
        <p style="color: #7f8c8d; margin: 0.5rem 0 0 0;">Controle completo dos Equipamentos de Proteção Individual</p>
    </div>
    <button class="btn-primary" onclick="openModal('create')" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
        ➕ Novo EPI
    </button>
</div>

<!-- Statistics Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="text-align: center; padding: 1.5rem;">
        <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">📊</div>
        <div style="font-size: 2rem; font-weight: bold; color: #2c3e50;" id="total-epis">0</div>
        <div style="color: #7f8c8d; font-size: 0.9rem;">Total de EPIs</div>
    </div>
    <div class="card" style="text-align: center; padding: 1.5rem;">
        <div style="background: linear-gradient(135deg, #48bb78, #38a169); color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">✅</div>
        <div style="font-size: 2rem; font-weight: bold; color: #2c3e50;" id="active-epis">0</div>
        <div style="color: #7f8c8d; font-size: 0.9rem;">Ativos</div>
    </div>
    <div class="card" style="text-align: center; padding: 1.5rem;">
        <div style="background: linear-gradient(135deg, #ed8936, #dd6b20); color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">⚠️</div>
        <div style="font-size: 2rem; font-weight: bold; color: #2c3e50;" id="maintenance-epis">0</div>
        <div style="color: #7f8c8d; font-size: 0.9rem;">Em Manutenção</div>
    </div>
    <div class="card" style="text-align: center; padding: 1.5rem;">
        <div style="background: linear-gradient(135deg, #f56565, #e53e3e); color: white; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem;">🚨</div>
        <div style="font-size: 2rem; font-weight: bold; color: #2c3e50;" id="expired-epis">0</div>
        <div style="color: #7f8c8d; font-size: 0.9rem;">Vencidos</div>
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
                <input type="text" id="search-input" placeholder="Nome, código ou fabricante..." style="flex: 1; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px 0 0 6px; font-size: 0.875rem;" />
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
                <option value="manutencao">Manutenção</option>
                <option value="descartado">Descartado</option>
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Tipo de EPI</label>
            <select id="tipo-filter" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;">
                <option value="">Todos os Tipos</option>
                <!-- Populated dynamically -->
            </select>
        </div>
        
        <div>
            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Ordenar por</label>
            <select id="order-filter" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 0.875rem;">
                <option value="created_at">Data de Criação</option>
                <option value="nome">Nome</option>
                <option value="data_vencimento">Vencimento</option>
            </select>
        </div>
    </div>
</div>

<!-- EPIs Table -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #ecf0f1;">
        <span id="results-info" style="color: #4a5568; font-size: 0.875rem;">Carregando EPIs...</span>
        <button onclick="refreshData()" style="background: transparent; color: #667eea; border: 1px solid #667eea; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
            🔄 Atualizar
        </button>
    </div>
    
    <div style="position: relative;">
        <div id="loading-state" style="padding: 4rem 2rem; text-align: center; color: #718096;">
            <div style="width: 40px; height: 40px; border: 4px solid #e2e8f0; border-top: 4px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 1rem;"></div>
            <p>Carregando dados...</p>
        </div>
        
        <div id="empty-state" style="display: none; padding: 4rem 2rem; text-align: center; color: #718096;">
            <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">📦</div>
            <h3 style="margin-bottom: 0.5rem; color: #4a5568;">Nenhum EPI encontrado</h3>
            <p style="margin-bottom: 1.5rem;">Não há EPIs cadastrados com os filtros aplicados.</p>
            <button onclick="openModal('create')" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Cadastrar Primeiro EPI
            </button>
        </div>
        
        <table id="epis-table" style="display: none; width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f7fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #4a5568; font-size: 0.875rem;">EPI</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Tipo</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Código</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Status</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Funcionário</th>
                    <th style="padding: 1rem; text-align: left; font-weight: 600; color: #4a5568; font-size: 0.875rem;">Vencimento</th>
                    <th style="padding: 1rem; text-align: center; font-weight: 600; color: #4a5568; font-size: 0.875rem; width: 120px;">Ações</th>
                </tr>
            </thead>
            <tbody id="epis-tbody">
                <!-- Populated dynamically -->
            </tbody>
        </table>
    </div>
</div>

<!-- EPI Modal -->
@include('layouts.cadastroepi')

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="modal" onclick="handleDeleteModalClick(event)">
    <div class="modal-content small" onclick="event.stopPropagation()">
        <div class="modal-header danger">
            <h2>Confirmar Exclusão</h2>
            <button class="close-btn" onclick="closeDeleteModal()">&times;</button>
        </div>
        
        <div class="modal-body">
            <div class="delete-content">
                <div class="delete-icon">🗑️</div>
                <h3>Tem certeza?</h3>
                <p>Esta ação não pode ser desfeita. O EPI <strong id="delete-epi-name"></strong> será removido permanentemente.</p>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                    Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn" onclick="confirmDelete()">
                    <span id="delete-text">Sim, Excluir</span>
                    <div id="delete-spinner" class="btn-spinner" style="display: none;"></div>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Container -->
<div id="notifications-container"></div>

@endsection

@push('styles')
<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }

    /* Status badges for table */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.ativo {
        background: #c6f6d5;
        color: #22543d;
    }

    .status-badge.inativo {
        background: #fed7d7;
        color: #742a2a;
    }

    .status-badge.manutencao {
        background: #feebc8;
        color: #7b341e;
    }

    .status-badge.descartado {
        background: #e9d8fd;
        color: #553c9a;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.875rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn.edit {
        background: #bee3f8;
        color: #2b6cb0;
    }

    .action-btn.edit:hover {
        background: #90cdf4;
    }

    .action-btn.delete {
        background: #fed7d7;
        color: #c53030;
    }

    .action-btn.delete:hover {
        background: #feb2b2;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 2000;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex;
        animation: modalFadeIn 0.3s ease;
    }

    @keyframes modalFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 800px;
        max-height: 90vh;
        overflow: hidden;
        animation: modalSlideIn 0.3s ease;
    }

    .modal-content.small {
        max-width: 500px;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>

<script>
// Global variables
let currentEpiId = null;
let deleteEpiId = null;
let episData = [];
let tiposEpi = [];
let funcionarios = [];

// API Configuration
const API_BASE = '/api';

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing app...');
    initializeApp();
});

async function initializeApp() {
    console.log('Starting app initialization...');
    try {
        showLoading();
        console.log('Loading data...');
        await Promise.all([
            loadTiposEpi(),
            loadFuncionarios(),
            loadEpis()
        ]);
        console.log('Data loaded, setting up event listeners...');
        setupEventListeners();
        hideLoading();
        console.log('App initialization complete!');
    } catch (error) {
        console.error('Erro ao inicializar aplicação:', error);
        showNotification('Erro ao carregar dados', 'error');
        hideLoading();
    }
}

function setupEventListeners() {
    // Search on Enter key
    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
        }
    });

    // Form submission
    document.getElementById('epi-form').addEventListener('submit', function(e) {
        e.preventDefault();
        handleFormSubmit();
    });

    // Auto-apply filters when changed
    document.getElementById('status-filter').addEventListener('change', applyFilters);
    document.getElementById('tipo-filter').addEventListener('change', applyFilters);
    document.getElementById('order-filter').addEventListener('change', applyFilters);

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeDeleteModal();
        }
    });
}

// API Functions
async function apiRequest(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            'Accept': 'application/json',
        },
    };

    const mergedOptions = {
        ...defaultOptions,
        ...options,
        headers: { ...defaultOptions.headers, ...options.headers },
    };

    const response = await fetch(url, mergedOptions);
    const data = await response.json();

    if (!response.ok) {
        throw new Error(data.message || 'Erro na requisição');
    }

    return data;
}

async function loadEpis() {
    try {
        const filters = getFilters();
        const queryParams = new URLSearchParams();
        
        Object.keys(filters).forEach(key => {
            if (filters[key]) {
                queryParams.append(key, filters[key]);
            }
        });

        const response = await apiRequest(`${API_BASE}/epis?${queryParams}`);
        episData = response.data || [];
        
        renderEpisTable();
        updateStatistics(response.statistics);
        updateResultsInfo();
    } catch (error) {
        console.error('Erro ao carregar EPIs:', error);
        showNotification('Erro ao carregar EPIs', 'error');
        showEmptyState();
    }
}

async function loadTiposEpi() {
    try {
        const response = await apiRequest(`${API_BASE}/tipos-epi`);
        tiposEpi = response.data || [];
        
        populateTiposSelect();
    } catch (error) {
        console.error('Erro ao carregar tipos de EPI:', error);
        // Fallback to hardcoded list if API fails
        tiposEpi = [
            { id: 1, nome: 'Capacete de Segurança', codigo: 'capacete', icone: '⛑️', cor: '#FF6B35' },
            { id: 2, nome: 'Óculos de Proteção', codigo: 'oculos', icone: '🥽', cor: '#4ECDC4' },
            { id: 3, nome: 'Luvas de Segurança', codigo: 'luvas', icone: '🧤', cor: '#45B7D1' },
            { id: 4, nome: 'Botas de Segurança', codigo: 'botas', icone: '🥾', cor: '#8B4513' },
            { id: 5, nome: 'Cinto de Segurança', codigo: 'cinto_seguranca', icone: '🔗', cor: '#9B59B6' },
            { id: 6, nome: 'Máscara de Proteção', codigo: 'mascara', icone: '😷', cor: '#E67E22' },
            { id: 7, nome: 'Protetor Auditivo', codigo: 'protetor_auditivo', icone: '🎧', cor: '#3498DB' },
            { id: 8, nome: 'Colete Refletivo', codigo: 'colete_refletivo', icone: '🦺', cor: '#F39C12' }
        ];
        populateTiposSelect();
    }
}

async function loadFuncionarios() {
    try {
        const response = await apiRequest(`${API_BASE}/funcionarios?paginate=false`);
        funcionarios = response.data || [];
        populateFuncionariosSelect();
    } catch (error) {
        console.error('Erro ao carregar funcionários:', error);
        // Don't show error notification for this as it's not critical
        funcionarios = [];
        populateFuncionariosSelect();
    }
}

// UI Functions
function showLoading() {
    document.getElementById('loading-state').style.display = 'block';
    document.getElementById('epis-table').style.display = 'none';
    document.getElementById('empty-state').style.display = 'none';
}

function hideLoading() {
    document.getElementById('loading-state').style.display = 'none';
}

function showEmptyState() {
    hideLoading();
    document.getElementById('empty-state').style.display = 'block';
    document.getElementById('epis-table').style.display = 'none';
}

function renderEpisTable() {
    hideLoading();
    
    if (episData.length === 0) {
        showEmptyState();
        return;
    }

    document.getElementById('empty-state').style.display = 'none';
    document.getElementById('epis-table').style.display = 'table';
    
    const tbody = document.getElementById('epis-tbody');
    tbody.innerHTML = '';

    episData.forEach(epi => {
        const row = createEpiRow(epi);
        tbody.appendChild(row);
    });
}

function createEpiRow(epi) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="font-weight: 600; color: #2d3748;">${epi.nome}</div>
                <div style="font-size: 0.75rem; color: #718096; font-family: monospace; background: #edf2f7; padding: 0.25rem 0.5rem; border-radius: 4px;">#${epi.id}</div>
            </div>
        </td>
        <td>
            <div class="type-badge" style="color: ${getTipoEpiById(epi.tipo_epi_id)?.cor || '#718096'};">
                <span>${getTipoEpiById(epi.tipo_epi_id)?.icone || '📋'}</span>
                <span>${getTipoEpiById(epi.tipo_epi_id)?.nome || 'N/A'}</span>
            </div>
        </td>
        <td>
            <code style="background: #edf2f7; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.875rem;">
                ${epi.codigo}
            </code>
        </td>
        <td>
            <span class="status-badge ${epi.status}">
                ${getStatusIcon(epi.status)}
                ${epi.status}
            </span>
        </td>
        <td>
            ${epi.funcionario ? epi.funcionario.nome : '<span style="color: #a0aec0; font-style: italic;">Não atribuído</span>'}
        </td>
        <td>
            ${formatVencimento(epi.data_vencimento)}
        </td>
        <td class="actions-column">
            <div class="action-buttons">
                <button class="action-btn edit" onclick="editEpi(${epi.id})" title="Editar">
                    ✏️
                </button>
                <button class="action-btn delete" onclick="openDeleteModal(${epi.id}, '${epi.nome}')" title="Excluir">
                    🗑️
                </button>
            </div>
        </td>
    `;
    return tr;
}

// Utility Functions
function getTipoEpiById(id) {
    return tiposEpi.find(tipo => tipo.id == id);
}

function getStatusIcon(status) {
    const icons = {
        ativo: '✅',
        inativo: '❌',
        manutencao: '🔧',
        descartado: '🗑️'
    };
    return icons[status] || '❓';
}

function formatVencimento(dataVencimento) {
    if (!dataVencimento) {
        return '<span style="color: #a0aec0; font-style: italic;">Sem vencimento</span>';
    }

    const vencimento = new Date(dataVencimento);
    const hoje = new Date();
    const diffTime = vencimento.getTime() - hoje.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    const formatDate = vencimento.toLocaleDateString('pt-BR');

    if (diffDays < 0) {
        return `<span style="color: #e53e3e; font-weight: 600;">${formatDate} (Vencido)</span>`;
    } else if (diffDays <= 30) {
        return `<span style="color: #dd6b20; font-weight: 600;">${formatDate} (${diffDays} dias)</span>`;
    } else {
        return `<span style="color: #38a169;">${formatDate}</span>`;
    }
}

function populateTiposSelect() {
    const selects = ['tipo-filter', 'tipo_epi_id'];
    
    selects.forEach(selectId => {
        const select = document.getElementById(selectId);
        if (!select) return;
        
        // Clear existing options (except first one for filter)
        if (selectId === 'tipo-filter') {
            select.innerHTML = '<option value="">Todos os Tipos</option>';
        } else {
            select.innerHTML = '<option value="">Selecione um tipo</option>';
        }
        
        tiposEpi.forEach(tipo => {
            const option = document.createElement('option');
            option.value = tipo.id;
            option.textContent = `${tipo.icone || '📋'} ${tipo.nome}`;
            select.appendChild(option);
        });
    });
}

function populateFuncionariosSelect() {
    const select = document.getElementById('funcionario_id');
    if (!select) return;
    
    select.innerHTML = '<option value="">Não atribuído</option>';
    
    funcionarios.forEach(funcionario => {
        const option = document.createElement('option');
        option.value = funcionario.id;
        option.textContent = funcionario.nome;
        select.appendChild(option);
    });
}

function getFilters() {
    return {
        search: document.getElementById('search-input')?.value || '',
        status: document.getElementById('status-filter')?.value || '',
        tipo_epi_id: document.getElementById('tipo-filter')?.value || '',
        order_by: document.getElementById('order-filter')?.value || 'created_at'
    };
}

function clearFilters() {
    document.getElementById('search-input').value = '';
    document.getElementById('status-filter').value = '';
    document.getElementById('tipo-filter').value = '';
    document.getElementById('order-filter').value = 'created_at';
    applyFilters();
}

function applyFilters() {
    loadEpis();
}

function refreshData() {
    loadEpis();
    showNotification('Dados atualizados!', 'success');
}

function updateStatistics(apiStats = null) {
    if (apiStats) {
        // Use statistics from API
        document.getElementById('total-epis').textContent = apiStats.total || 0;
        document.getElementById('active-epis').textContent = apiStats.ativo || 0;
        document.getElementById('maintenance-epis').textContent = apiStats.manutencao || 0;
        document.getElementById('expired-epis').textContent = apiStats.vencidos || 0;
    } else {
        // Fallback to client-side calculation
        const total = episData.length;
        const ativos = episData.filter(epi => epi.status === 'ativo').length;
        const manutencao = episData.filter(epi => epi.status === 'manutencao').length;
        
        const hoje = new Date();
        const vencidos = episData.filter(epi => {
            if (!epi.data_vencimento) return false;
            return new Date(epi.data_vencimento) < hoje;
        }).length;

        document.getElementById('total-epis').textContent = total;
        document.getElementById('active-epis').textContent = ativos;
        document.getElementById('maintenance-epis').textContent = manutencao;
        document.getElementById('expired-epis').textContent = vencidos;
    }
}

function updateResultsInfo() {
    const total = episData.length;
    const filters = getFilters();
    let info = `Exibindo ${total} EPI${total !== 1 ? 's' : ''}`;
    
    if (filters.search) {
        info += ` para "${filters.search}"`;
    }
    
    document.getElementById('results-info').textContent = info;
}

// Modal Functions
function openModal(mode, epiId = null) {
    console.log('openModal called with mode:', mode, 'epiId:', epiId);
    currentEpiId = epiId;
    const modal = document.getElementById('epi-modal');
    const title = document.getElementById('modal-title');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');

    console.log('Modal elements found:', {
        modal: !!modal,
        title: !!title,
        submitBtn: !!submitBtn,
        submitText: !!submitText
    });

    if (mode === 'create') {
        title.textContent = 'Novo EPI';
        submitText.textContent = 'Salvar EPI';
        clearForm();
    } else if (mode === 'edit' && epiId) {
        title.textContent = 'Editar EPI';
        submitText.textContent = 'Atualizar EPI';
        loadEpiData(epiId);
    }

    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    console.log('Modal should now be visible');
}

function closeModal() {
    const modal = document.getElementById('epi-modal');
    modal.classList.remove('show');
    document.body.style.overflow = '';
    clearForm();
    clearErrors();
    currentEpiId = null;
}

function handleModalClick(event) {
    if (event.target.id === 'epi-modal') {
        closeModal();
    }
}

function editEpi(id) {
    openModal('edit', id);
}

async function loadEpiData(id) {
    try {
        const response = await apiRequest(`${API_BASE}/epis/${id}`);
        const epi = response.data;
        
        // Populate form fields
        document.getElementById('nome').value = epi.nome || '';
        document.getElementById('tipo_epi_id').value = epi.tipo_epi_id || '';
        document.getElementById('codigo').value = epi.codigo || '';
        document.getElementById('fabricante').value = epi.fabricante || '';
        document.getElementById('lote').value = epi.lote || '';
        document.getElementById('status').value = epi.status || 'ativo';
        document.getElementById('funcionario_id').value = epi.funcionario_id || '';
        document.getElementById('data_aquisicao').value = epi.data_aquisicao || '';
        document.getElementById('data_vencimento').value = epi.data_vencimento || '';
        document.getElementById('descricao').value = epi.descricao || '';
        
    } catch (error) {
        console.error('Erro ao carregar EPI:', error);
        showNotification('Erro ao carregar dados do EPI', 'error');
        closeModal();
    }
}

function clearForm() {
    const form = document.getElementById('epi-form');
    form.reset();
}

function clearErrors() {
    document.querySelectorAll('.form-group.error').forEach(group => {
        group.classList.remove('error');
    });
    document.querySelectorAll('.error-message.show').forEach(error => {
        error.classList.remove('show');
    });
}

async function handleFormSubmit() {
    clearErrors();
    
    const formData = getFormData();
    const isEdit = currentEpiId !== null;
    
    try {
        setSubmitLoading(true);
        
        let response;
        if (isEdit) {
            response = await apiRequest(`${API_BASE}/epis/${currentEpiId}`, {
                method: 'PUT',
                body: JSON.stringify(formData)
            });
        } else {
            response = await apiRequest(`${API_BASE}/epis`, {
                method: 'POST',
                body: JSON.stringify(formData)
            });
        }
        
        showNotification(response.message, 'success');
        closeModal();
        await loadEpis();
        
    } catch (error) {
        console.error('Erro ao salvar EPI:', error);
        
        if (error.message.includes('422') || error.message.includes('validation')) {
            showNotification('Por favor, verifique os dados informados', 'error');
            // Handle validation errors if available
        } else {
            showNotification('Erro ao salvar EPI. Tente novamente.', 'error');
        }
    } finally {
        setSubmitLoading(false);
    }
}

function getFormData() {
    const form = document.getElementById('epi-form');
    const formData = new FormData(form);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            data[key] = value;
        }
    }
    
    return data;
}

function setSubmitLoading(loading) {
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');
    
    if (loading) {
        submitBtn.disabled = true;
        submitText.style.display = 'none';
        submitSpinner.style.display = 'block';
    } else {
        submitBtn.disabled = false;
        submitText.style.display = 'block';
        submitSpinner.style.display = 'none';
    }
}

// Delete Functions
function openDeleteModal(id, name) {
    deleteEpiId = id;
    document.getElementById('delete-epi-name').textContent = name;
    document.getElementById('delete-modal').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.remove('show');
    document.body.style.overflow = '';
    deleteEpiId = null;
}

function handleDeleteModalClick(event) {
    if (event.target.id === 'delete-modal') {
        closeDeleteModal();
    }
}

async function confirmDelete() {
    if (!deleteEpiId) return;
    
    try {
        setDeleteLoading(true);
        
        const response = await apiRequest(`${API_BASE}/epis/${deleteEpiId}`, {
            method: 'DELETE'
        });
        
        showNotification(response.message, 'success');
        closeDeleteModal();
        await loadEpis();
        
    } catch (error) {
        console.error('Erro ao excluir EPI:', error);
        showNotification('Erro ao excluir EPI. Tente novamente.', 'error');
    } finally {
        setDeleteLoading(false);
    }
}

function setDeleteLoading(loading) {
    const deleteBtn = document.getElementById('confirm-delete-btn');
    const deleteText = document.getElementById('delete-text');
    const deleteSpinner = document.getElementById('delete-spinner');
    
    if (loading) {
        deleteBtn.disabled = true;
        deleteText.style.display = 'none';
        deleteSpinner.style.display = 'block';
    } else {
        deleteBtn.disabled = false;
        deleteText.style.display = 'block';
        deleteSpinner.style.display = 'none';
    }
}

// Notification System
function showNotification(message, type = 'info', title = null) {
    const container = document.getElementById('notifications-container');
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    
    const defaultTitles = {
        success: 'Sucesso!',
        error: 'Erro!',
        warning: 'Atenção!',
        info: 'Informação'
    };
    
    const notificationTitle = title || defaultTitles[type];
    
    notification.innerHTML = `
        <div class="notification-content">
            <div class="notification-title">${notificationTitle}</div>
            <div class="notification-message">${message}</div>
        </div>
        <button class="notification-close" onclick="removeNotification(this.parentElement)">&times;</button>
    `;
    
    container.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        removeNotification(notification);
    }, 5000);
}

function removeNotification(notification) {
    if (notification && notification.parentNode) {
        notification.style.animation = 'notificationSlide 0.3s ease reverse';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
}
</script>