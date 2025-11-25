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

        /* Modal Overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-overlay.active {
            display: flex !important;
            justify-content: center;
            align-items: center;
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
    <!-- Modal -->
    <div class="modal-overlay" id="cadastroModal">
        <div class="modal-container">
            <!-- Header -->
            <div class="modal-header">
                <h2 id="modalTitle">📋 Novo EPI</h2>
                <button type="button" class="close-btn" onclick="fecharModal()" title="Fechar">&times;</button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <!-- Alert Messages -->
                <div class="alert alert-success" id="successAlert"></div>
                <div class="alert alert-danger" id="errorAlert"></div>
                <div class="alert alert-warning" id="warningAlert"></div>

                <!-- Form -->
                <form id="cadastroForm">
                    @csrf
                    <input type="hidden" id="epiId" name="epi_id" value="">

                    <!-- Nome do EPI -->
                    <div class="form-group">
                        <label for="nome">Nome do EPI *</label>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            placeholder="Ex: Capacete de Segurança"
                            required
                        >
                    </div>

                    <!-- Tipo e Categoria -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tipo">Tipo *</label>
                            <select id="tipo" name="tipo" required>
                                <option value="">Selecione o tipo</option>
                                <option value="capacete">Capacete</option>
                                <option value="luva">Luva</option>
                                <option value="bota">Bota</option>
                                <option value="oculos">Óculos</option>
                                <option value="mascara">Máscara</option>
                                <option value="coletes">Colete</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="categoria">Categoria *</label>
                            <select id="categoria" name="categoria" required>
                                <option value="">Selecione a categoria</option>
                                <option value="protecao_cabeca">Proteção da Cabeça</option>
                                <option value="protecao_olhos">Proteção dos Olhos</option>
                                <option value="protecao_auricular">Proteção Auricular</option>
                                <option value="protecao_respiratoria">Proteção Respiratória</option>
                                <option value="protecao_membros">Proteção de Membros</option>
                                <option value="protecao_corpo">Proteção do Corpo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Quantidade e Norma -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="quantidade">Quantidade em Estoque *</label>
                            <input 
                                type="number" 
                                id="quantidade" 
                                name="quantidade" 
                                placeholder="0"
                                min="0"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="norma">Norma Técnica</label>
                            <input 
                                type="text" 
                                id="norma" 
                                name="norma" 
                                placeholder="Ex: NBR 12245"
                            >
                        </div>
                    </div>

                    <!-- Data de Validade -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="data_validade">Data de Validade</label>
                            <input 
                                type="date" 
                                id="data_validade" 
                                name="data_validade"
                            >
                        </div>

                        <div class="form-group">
                            <label for="data_aquisicao">Data de Aquisição</label>
                            <input 
                                type="date" 
                                id="data_aquisicao" 
                                name="data_aquisicao"
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

                    <!-- Fabricante e Modelo -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fabricante">Fabricante</label>
                            <input 
                                type="text" 
                                id="fabricante" 
                                name="fabricante" 
                                placeholder="Ex: 3M do Brasil"
                            >
                        </div>

                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <input 
                                type="text" 
                                id="modelo" 
                                name="modelo" 
                                placeholder="Ex: H-700"
                            >
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="fecharModal()">
                    ✕ Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="enviarFormulario()">
                    <span class="loading-spinner" id="loadingSpinner"></span>
                    <span id="btnText">💾 Salvar</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        let modoEdicao = false;

        // Abrir Modal para novo EPI
        function abrirModal() {
            modoEdicao = false;
            document.getElementById('modalTitle').textContent = '📋 Novo EPI';
            document.getElementById('btnText').textContent = '💾 Salvar';
            document.getElementById('epiId').value = '';
            limparFormulario();
            document.getElementById('cadastroModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Fechar Modal
        function fecharModal() {
            document.getElementById('cadastroModal').classList.remove('active');
            document.body.style.overflow = 'auto';
            limparFormulario();
            modoEdicao = false;
        }

        // Limpar Formulário
        function limparFormulario() {
            document.getElementById('cadastroForm').reset();
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            const warningAlert = document.getElementById('warningAlert');
            
            if (successAlert) successAlert.classList.remove('active');
            if (errorAlert) errorAlert.classList.remove('active');
            if (warningAlert) warningAlert.classList.remove('active');
        }

        // Mostrar Alertas
        function mostraAlerta(mensagem, tipo) {
            const alertId = `${tipo}Alert`;
            const alertElement = document.getElementById(alertId);
            
            if (alertElement) {
                alertElement.textContent = mensagem;
                alertElement.classList.add('active');

                setTimeout(() => {
                    alertElement.classList.remove('active');
                }, 5000);
            } else {
                console.warn(`Alert element com ID ${alertId} não encontrado`);
            }
        }

        // Enviar Formulário
        function enviarFormulario() {
            const form = document.getElementById('cadastroForm');
            if (form.checkValidity() === false) {
                mostraAlerta('Por favor, preencha todos os campos obrigatórios.', 'warning');
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

                const data = await response.json();
                
                if (data.success) {
                    const epi = data.data;
                    console.log('EPI encontrado:', epi);
                    
                    // Preencher formulário
                    document.getElementById('epiId').value = epi.id;
                    document.getElementById('nome').value = epi.nome || '';
                    document.getElementById('tipo').value = epi.tipo || '';
                    document.getElementById('categoria').value = epi.categoria || '';
                    document.getElementById('quantidade').value = epi.quantidade || 0;
                    document.getElementById('norma').value = epi.norma || '';
                    document.getElementById('data_validade').value = epi.data_validade || '';
                    document.getElementById('data_aquisicao').value = epi.data_aquisicao || '';
                    document.getElementById('descricao').value = epi.descricao || '';
                    document.getElementById('fabricante').value = epi.fabricante || '';
                    document.getElementById('modelo').value = epi.modelo || '';
                    
                    // Trocar para modo edição
                    modoEdicao = true;
                    document.getElementById('modalTitle').textContent = '✏️ Editar EPI';
                    document.getElementById('btnText').textContent = '🔄 Atualizar';
                    
                    document.getElementById('cadastroModal').classList.add('active');
                    document.body.style.overflow = 'hidden';
                } else {
                    mostraAlerta(data.message || 'EPI não encontrado', 'danger');
                }
            } catch (error) {
                console.error('Erro ao buscar EPI:', error);
                mostraAlerta('Erro ao buscar EPI: ' + error.message, 'danger');
            }
        }

        // Salvar novo EPI
        async function salvarEPI() {
            const form = document.getElementById('cadastroForm');
            const formData = new FormData(form);
            const loadingSpinner = document.getElementById('loadingSpinner');

            loadingSpinner.classList.add('active');

            try {
                const response = await fetch('/api/epis', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    mostraAlerta('EPI cadastrado com sucesso!', 'success');
                    
                    setTimeout(() => {
                        fecharModal();
                        location.reload();
                    }, 2000);
                } else {
                    mostraAlerta(data.message || 'Erro ao cadastrar EPI', 'danger');
                }
            } catch (error) {
                console.error('Erro:', error);
                mostraAlerta('Erro ao processar requisição.', 'danger');
            } finally {
                loadingSpinner.classList.remove('active');
            }
        }

        // Atualizar EPI
        async function atualizarEPI() {
            const id = document.getElementById('epiId').value;
            const form = document.getElementById('cadastroForm');
            const formData = new FormData(form);
            const loadingSpinner = document.getElementById('loadingSpinner');

            loadingSpinner.classList.add('active');

            try {
                const response = await fetch(`/api/epis/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    mostraAlerta('EPI atualizado com sucesso!', 'success');
                    
                    setTimeout(() => {
                        fecharModal();
                        location.reload();
                    }, 2000);
                } else {
                    mostraAlerta(data.message || 'Erro ao atualizar', 'danger');
                }
            } catch (error) {
                console.error('Erro:', error);
                mostraAlerta('Erro ao processar requisição.', 'danger');
            } finally {
                loadingSpinner.classList.remove('active');
            }
        }

        // Deletar EPI
        async function deletarEPI(id) {
            if (confirm('Tem certeza que deseja deletar este EPI?')) {
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
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        mostraAlerta(data.message || 'Erro ao deletar', 'danger');
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    mostraAlerta('Erro ao deletar EPI', 'danger');
                }
            }
        }

        // Fechar modal ao clicar fora
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('cadastroModal');
            if (event.target === modal) {
                fecharModal();
            }
        });

        // Fechar modal com Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                fecharModal();
            }
        });
    </script>
</body>
</html>