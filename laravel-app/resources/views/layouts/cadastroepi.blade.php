<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cadastro de EPI</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Modal Overlay - Versão corrigida */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            display: none;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-overlay.active {
            display: flex !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Modal Container */
        .modal-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
            animation: slideIn 0.3s ease-in-out;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Modal Header */
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px 12px 0 0;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: transform 0.2s;
            line-height: 1;
            padding: 0;
        }

        .close-btn:hover {
            transform: scale(1.2);
        }

        /* Modal Body */
        .modal-body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 1rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        /* Alert Messages */
        .alert {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 6px;
            display: none;
        }

        .alert.active {
            display: block;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
        }

        /* Modal Footer */
        .modal-footer {
            padding: 2rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            background: #f8f9fa;
            border-radius: 0 0 12px 12px;
            border-top: 1px solid #e0e0e0;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #2c3e50;
        }

        .btn-secondary:hover {
            background: #d0d0d0;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-spinner.active {
            display: block;
        }

        /* Toast Notifications - Centralized */
        .toast-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            display: none;
            width: 85%;
            max-width: 320px;
        }

        .toast-container.active {
            display: block;
        }

        .toast {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
            font-size: 0.95rem;
            font-weight: 600;
            animation: toastSlideIn 0.3s ease-out;
            backdrop-filter: blur(10px);
            border: 2px solid;
            cursor: pointer;
            transition: box-shadow 0.2s ease, opacity 0.2s ease;
        }

        .toast:hover {
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
            opacity: 0.95;
        }

        .toast-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border-color: #1e7e34;
        }

        .toast-danger {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
            color: white;
            border-color: #bd2130;
        }

        .toast-warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: #212529;
            border-color: #d39e00;
        }

        @keyframes toastSlideIn {
            from {
                opacity: 0;
                transform: translate(-50%, -80%);
                scale: 0.8;
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
                scale: 1;
            }
        }

        .toast-icon {
            font-size: 1.5rem;
            margin-bottom: 0.3rem;
            display: block;
        }

        /* Responsive */
        @media (max-width: 600px) {
            .modal-container {
                width: 95%;
                max-height: 95vh;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 1.5rem;
            }

            .modal-header h2 {
                font-size: 1.2rem;
            }

            .modal-footer {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <!-- Toast Notifications -->
    <div class="toast-container" id="toast-container">
        <div class="toast" id="toast-message" title="Clique para fechar">
            <span class="toast-icon" id="toast-icon"></span>
            <div id="toast-text"></div>
            <small style="opacity: 0.6; font-size: 0.7rem; margin-top: 0.3rem; display: block; font-weight: 400;">
                Clique para fechar
            </small>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal modal-overlay" id="epi-modal">
        <div class="modal-container modal-content" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="modal-header">
                <h2 id="modal-title">📋 Novo EPI</h2>
                <button type="button" class="close-btn" id="modal-close-btn" title="Fechar">&times;</button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <!-- Alert Messages -->
                <div class="alert alert-success" id="successAlert"></div>
                <div class="alert alert-danger" id="errorAlert"></div>
                <div class="alert alert-warning" id="warningAlert"></div>

                <!-- Form -->
                <form id="epi-form">
                    @csrf
                    <input type="hidden" id="epiId" name="epi_id" value="">

                    <!-- Nome do EPI -->
                    <div class="form-group">
                        <label for="nome">Nome do EPI *</label>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            placeholder="Ex: Capacete de Segurança Branco"
                            required
                        >
                    </div>

                    <!-- Tipo do EPI -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tipo">Tipo de EPI *</label>
                            <select id="tipo_epi_id" name="tipo_epi_id" required>
                                <option value="">Selecione o tipo</option>
                                <!-- Populated dynamically via JavaScript -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="codigo">Código *</label>
                            <input 
                                type="text" 
                                id="codigo" 
                                name="codigo" 
                                placeholder="Ex: CAP001"
                                required
                            >
                        </div>
                    </div>

                    <!-- Status e Fabricante -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo</option>
                                <option value="manutencao">Manutenção</option>
                                <option value="descartado">Descartado</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fabricante">Fabricante</label>
                            <input 
                                type="text" 
                                id="fabricante" 
                                name="fabricante" 
                                placeholder="Ex: 3M, MSA, Honeywell"
                            >
                        </div>
                    </div>

                    <!-- Lote e Funcionário -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="lote">Lote</label>
                            <input 
                                type="text" 
                                id="lote" 
                                name="lote" 
                                placeholder="Ex: LT2024001"
                            >
                        </div>

                        <div class="form-group">
                            <label for="funcionario_id">Funcionário (Opcional)</label>
                            <select id="funcionario_id" name="funcionario_id">
                                <option value="">Não atribuído</option>
                                <!-- Funcionários serão carregados via JavaScript -->
                            </select>
                        </div>
                    </div>

                    <!-- Data de Aquisição e Vencimento -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="data_aquisicao">Data de Aquisição</label>
                            <input 
                                type="date" 
                                id="data_aquisicao" 
                                name="data_aquisicao"
                            >
                        </div>

                        <div class="form-group">
                            <label for="data_vencimento">Data de Vencimento</label>
                            <input 
                                type="date" 
                                id="data_vencimento" 
                                name="data_vencimento"
                            >
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="form-group full">
                        <label for="descricao">Descrição</label>
                        <textarea 
                            id="descricao" 
                            name="descricao" 
                            placeholder="Adicione informações adicionais sobre o EPI..."
                        ></textarea>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="fecharModal()">
                    ✕ Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="submit-btn" onclick="handleFormSubmission()">
                    <span id="submit-text">💾 Salvar EPI</span>
                    <div id="submit-spinner" class="loading-spinner" style="display: none;"></div>
                </button>
            </div>
        </div>
    </div>

    <script>
        let modoEdicao = false;

        // Abrir Modal para novo EPI
        function abrirModal() {
            modoEdicao = false;
            document.getElementById('modal-title').textContent = '📋 Novo EPI';
            document.getElementById('submit-text').textContent = '💾 Salvar EPI';
            document.getElementById('epiId').value = '';
            limparFormulario();
            document.getElementById('epi-modal').classList.add('active');
            document.body.style.overflow = 'hidden';
            carregarTiposEpi(); // Carregar tipos de EPI ao abrir modal
            carregarFuncionarios(); // Carregar funcionários ao abrir modal
        }

        // Fechar Modal
        function fecharModal() {
            console.log('=== FECHAR MODAL FORÇADO ===');
            
            const modal = document.getElementById('epi-modal');
            
            if (modal) {
                // Múltiplas tentativas de fechar
                modal.classList.remove('active');
                modal.style.display = 'none';
                
                // Forçar após um delay
                setTimeout(() => {
                    modal.classList.remove('active');
                    modal.style.display = 'none';
                }, 10);
                
                console.log('Modal forçado a fechar');
            }
            
            // Restaurar scroll
            document.body.style.overflow = 'auto';
            document.body.style.overflow = '';
            
            // Limpar e resetar
            limparFormulario();
            modoEdicao = false;
        }

        // Limpar Formulário
        function limparFormulario() {
            document.getElementById('epi-form').reset();
            const alerts = ['successAlert', 'errorAlert', 'warningAlert'];
            alerts.forEach(alertId => {
                const alert = document.getElementById(alertId);
                if (alert) alert.classList.remove('active');
            });
        }

        // Mostrar Alertas (Toast Centralizado)
        function mostraAlerta(mensagem, tipo) {
            const toastContainer = document.getElementById('toast-container');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');
            const toastText = document.getElementById('toast-text');
            
            // Definir ícone e classes baseado no tipo
            let icon = '';
            let classes = 'toast ';
            
            switch(tipo) {
                case 'success':
                    icon = '✅';
                    classes += 'toast-success';
                    break;
                case 'danger':
                    icon = '❌';
                    classes += 'toast-danger';
                    break;
                case 'warning':
                    icon = '⚠️';
                    classes += 'toast-warning';
                    break;
                default:
                    icon = 'ℹ️';
                    classes += 'toast-info';
            }
            
            // Configurar o toast
            toastIcon.textContent = icon;
            toastText.textContent = mensagem;
            toastMessage.className = classes;
            
            // Mostrar o toast
            toastContainer.classList.add('active');
            
            // Esconder automaticamente após 4 segundos
            setTimeout(() => {
                toastContainer.classList.remove('active');
            }, 4000);
            
            // Também mostrar o alerta no modal (para compatibilidade)
            const alertId = `${tipo}Alert`;
            const alertElement = document.getElementById(alertId);
            
            if (alertElement) {
                alertElement.textContent = mensagem;
                alertElement.classList.add('active');

                setTimeout(() => {
                    alertElement.classList.remove('active');
                }, 5000);
            }
        }

        // Enviar Formulário
        function enviarFormulario() {
            const form = document.getElementById('epi-form');
            if (!form.checkValidity()) {
                mostraAlerta('Por favor, preencha todos os campos obrigatórios.', 'warning');
                form.reportValidity();
                return;
            }

            if (modoEdicao) {
                atualizarEPI();
            } else {
                salvarEPI();
            }
        }

        // Editar EPI
        async function editarEPI(id) {
            console.log('Editando EPI ID:', id);
            
            try {
                const response = await fetch(`/api/epis/${id}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();
                
                if (data.success) {
                    const epi = data.data;
                    console.log('EPI encontrado:', epi);
                    
                    // Aguardar tipos e funcionários carregarem antes de preencher
                    await carregarTiposEpi();
                    await carregarFuncionarios();
                    
                    // Preencher formulário
                    document.getElementById('epiId').value = epi.id;
                    document.getElementById('nome').value = epi.nome || '';
                    document.getElementById('tipo_epi_id').value = epi.tipo_epi_id || '';
                    document.getElementById('codigo').value = epi.codigo || '';
                    document.getElementById('status').value = epi.status || 'ativo';
                    document.getElementById('fabricante').value = epi.fabricante || '';
                    document.getElementById('lote').value = epi.lote || '';
                    document.getElementById('funcionario_id').value = epi.funcionario_id || '';
                    document.getElementById('data_aquisicao').value = epi.data_aquisicao || '';
                    document.getElementById('data_vencimento').value = epi.data_vencimento || '';
                    document.getElementById('descricao').value = epi.descricao || '';
                    
                    // Trocar para modo edição
                    modoEdicao = true;
                    document.getElementById('modal-title').textContent = '✏️ Editar EPI';
                    document.getElementById('submit-text').textContent = '🔄 Atualizar EPI';
                    
                    document.getElementById('epi-modal').classList.add('active');
                    document.body.style.overflow = 'hidden';
                } else {
                    mostraAlerta(data.message || 'EPI não encontrado', 'danger');
                }
            } catch (error) {
                console.error('Erro ao buscar EPI:', error);
                mostraAlerta('Erro ao buscar EPI: ' + error.message, 'danger');
            }
        }

        // Criar objeto com dados do formulário
        function obterDadosFormulario() {
            const form = document.getElementById('epi-form');
            const formData = new FormData(form);
            
            const data = {
                nome: formData.get('nome'),
                tipo_epi_id: formData.get('tipo_epi_id'), // Corrigido para usar tipo_epi_id
                codigo: formData.get('codigo'),
                status: formData.get('status') || 'ativo',
                fabricante: formData.get('fabricante'),
                lote: formData.get('lote'),
                funcionario_id: formData.get('funcionario_id') || null,
                data_aquisicao: formData.get('data_aquisicao') || null,
                data_vencimento: formData.get('data_vencimento') || null,
                descricao: formData.get('descricao'),
            };
            
            console.log('Dados do formulário:', data);
            console.log('Modo edição:', modoEdicao);
            
            return data;
        }

        // Salvar novo EPI
        async function salvarEPI() {
            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const submitSpinner = document.getElementById('submit-spinner');
            
            // Mostrar loading
            submitBtn.disabled = true;
            submitText.style.display = 'none';
            submitSpinner.style.display = 'block';

            try {
                const dadosEPI = obterDadosFormulario();

                const response = await fetch('/api/epis', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(dadosEPI)
                });

                const data = await response.json();

                if (data.success) {
                    mostraAlerta('EPI cadastrado com sucesso!', 'success');
                    limparFormulario();
                    
                    setTimeout(() => {
                        fecharModal();
                        if (typeof window.recarregarTabela === 'function') {
                            window.recarregarTabela();
                        } else {
                            location.reload();
                        }
                    }, 1500);
                } else {
                    const erro = data.message || 'Erro ao cadastrar EPI';
                    mostraAlerta(erro, 'danger');
                }
            } catch (error) {
                console.error('Erro:', error);
                mostraAlerta('Erro ao processar requisição. Tente novamente.', 'danger');
            } finally {
                // Esconder loading
                submitBtn.disabled = false;
                submitText.style.display = 'block';
                submitSpinner.style.display = 'none';
            }
        }

        // Atualizar EPI
        async function atualizarEPI() {
            const id = document.getElementById('epiId').value;
            if (!id) {
                mostraAlerta('ID do EPI não encontrado', 'danger');
                return;
            }

            const submitBtn = document.getElementById('submit-btn');
            const submitText = document.getElementById('submit-text');
            const submitSpinner = document.getElementById('submit-spinner');
            
            // Mostrar loading
            submitBtn.disabled = true;
            submitText.style.display = 'none';
            submitSpinner.style.display = 'block';

            try {
                const dadosEPI = obterDadosFormulario();
                console.log('=== DEBUG ATUALIZAÇÃO EPI ===');
                console.log('ID do EPI:', id, '(tipo:', typeof id, ')');
                console.log('Dados sendo enviados:', dadosEPI);
                console.log('URL da requisição:', `/api/epis/${id}`);
                console.log('============================');

                const response = await fetch(`/api/epis/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(dadosEPI)
                });

                const data = await response.json();

                if (data.success) {
                    mostraAlerta('EPI atualizado com sucesso!', 'success');
                    
                    setTimeout(() => {
                        // Remover loading
                        submitBtn.disabled = false;
                        submitText.style.display = 'block';
                        submitSpinner.style.display = 'none';
                        
                        fecharModal();
                        if (typeof window.recarregarTabela === 'function') {
                            window.recarregarTabela();
                        } else {
                            location.reload();
                        }
                    }, 1500);
                } else {
                    // Remover loading
                    submitBtn.disabled = false;
                    submitText.style.display = 'block';
                    submitSpinner.style.display = 'none';
                    
                    console.error('Erro na resposta da API:', data);
                    
                    // Mostrar erros mais detalhados se disponível
                    let errorMessage = data.message || 'Erro ao atualizar EPI';
                    if (data.errors) {
                        const validationErrors = Object.values(data.errors).flat();
                        errorMessage += ': ' + validationErrors.join(', ');
                    }
                    
                    mostraAlerta(errorMessage, 'danger');
                }
            } catch (error) {
                console.error('Erro na requisição:', error);
                
                // Remover loading
                submitBtn.disabled = false;
                submitText.style.display = 'block';
                submitSpinner.style.display = 'none';
                
                let errorMessage = 'Erro ao processar requisição';
                if (error.message) {
                    errorMessage += ': ' + error.message;
                }
                
                mostraAlerta(errorMessage, 'danger');
            }
        }

        // Deletar EPI
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
                
                if (data.success) {
                    mostraAlerta(data.message, 'success');
                    setTimeout(() => {
                        if (typeof window.recarregarTabela === 'function') {
                            window.recarregarTabela();
                        } else {
                            location.reload();
                        }
                    }, 1000);
                } else {
                    mostraAlerta(data.message || 'Erro ao deletar EPI', 'danger');
                }
            } catch (error) {
                console.error('Erro:', error);
                mostraAlerta('Erro ao deletar EPI', 'danger');
            }
        }

        // Carregar tipos de EPI no select
        async function carregarTiposEpi() {
            try {
                const response = await fetch('/api/tipos-epi', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const tipos = await response.json();
                const select = document.getElementById('tipo_epi_id');
                
                // Limpar options atuais
                select.innerHTML = '<option value="">Selecione o tipo</option>';
                
                // Verificar se é array ou objeto com dados
                const listaTipos = Array.isArray(tipos) ? tipos : tipos.data || [];
                
                listaTipos.forEach(tipo => {
                    const option = document.createElement('option');
                    option.value = tipo.id;
                    option.textContent = tipo.nome;
                    select.appendChild(option);
                });
                
            } catch (error) {
                console.error('Erro ao carregar tipos de EPI:', error);
                // Não mostrar alerta aqui para não interferir no fluxo
            }
        }

        // Carregar funcionários no select
        async function carregarFuncionarios() {
            try {
                const response = await fetch('/api/funcionarios', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const funcionarios = await response.json();
                const select = document.getElementById('funcionario_id');
                
                // Limpar options atuais
                select.innerHTML = '<option value="">Não atribuído</option>';
                
                // Verificar se é array ou objeto com dados
                const listaFuncionarios = Array.isArray(funcionarios) ? funcionarios : funcionarios.data || [];
                
                listaFuncionarios.forEach(funcionario => {
                    const option = document.createElement('option');
                    option.value = funcionario.id;
                    option.textContent = `${funcionario.nome} - ${funcionario.departamento || 'Sem depto'}`;
                    select.appendChild(option);
                });
                
            } catch (error) {
                console.error('Erro ao carregar funcionários:', error);
                // Não mostrar alerta aqui para não interferir no fluxo
            }
        }

        // Fechar toast ao clicar nele
        function fecharToast() {
            const toastContainer = document.getElementById('toast-container');
            toastContainer.classList.remove('active');
        }

        // Eventos de fechamento do modal
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('epi-modal');
            if (event.target === modal) {
                fecharModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                fecharModal();
            }
        });

        // Função para lidar com o envio do formulário
        function handleFormSubmission() {
            console.log('handleFormSubmission called, modo edição:', modoEdicao);
            
            const form = document.getElementById('epi-form');
            if (!form.checkValidity()) {
                mostraAlerta('Por favor, preencha todos os campos obrigatórios.', 'warning');
                form.reportValidity();
                return;
            }

            if (modoEdicao) {
                atualizarEPI();
            } else {
                salvarEPI();
            }
        }

        // Adicionar event listener para o formulário
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('epi-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    handleFormSubmission();
                });
            }

            // Event listener para fechar toast ao clicar
            const toastContainer = document.getElementById('toast-container');
            if (toastContainer) {
                toastContainer.addEventListener('click', fecharToast);
            }

            // Versão alternativa para o botão de fechar
            const closeBtn = document.getElementById('modal-close-btn');
            if (closeBtn) {
                closeBtn.onclick = function(e) {
                    e.preventDefault();
                    console.log('Botão fechar clicado - versão alternativa');
                    fecharModal();
                };
            }
        });

        // Expor função globalmente para uso em outras páginas
        window.editarEPI = editarEPI;
        window.deletarEPI = deletarEPI;
        window.abrirModal = abrirModal;
        window.fecharModal = fecharModal;
        window.handleFormSubmission = handleFormSubmission;
        window.carregarTiposEpi = carregarTiposEpi;
        window.mostraAlerta = mostraAlerta;
        window.fecharToast = fecharToast;
    </script>
</body>
</html>