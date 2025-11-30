<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cadastro de Funcionário</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Modal Container */
        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 900px;
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
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: transform 0.2s;
            line-height: 1;
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

        /* Tabs */
        .tabs-container {
            margin-bottom: 2rem;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #ecf0f1;
            margin-bottom: 2rem;
            overflow-x: auto;
        }

        .tab-button {
            background: none;
            border: none;
            padding: 1rem 1.5rem;
            cursor: pointer;
            font-weight: 500;
            color: #7f8c8d;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            white-space: nowrap;
            min-width: fit-content;
        }

        .tab-button:hover {
            color: #2c3e50;
            background: #f8f9fa;
        }

        .tab-button.active {
            color: #667eea;
            border-bottom-color: #667eea;
            background: linear-gradient(135deg, #667eea10, #764ba210);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        /* Form Layout */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.span-2 {
            grid-column: span 2;
        }

        .form-group.span-3 {
            grid-column: span 2;
        }

        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .required {
            color: #e74c3c;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group input:invalid,
        .form-group select:invalid {
            border-color: #e74c3c;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Field Errors */
        .field-error {
            color: #e74c3c;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            min-height: 1rem;
            display: none;
        }

        .field-error.show {
            display: block;
        }

        /* Section Titles */
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 2rem 0 1rem 0;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #ecf0f1;
        }

        /* Data Preview */
        .data-preview {
            background: #f8f9fa;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .preview-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #ecf0f1;
        }

        .preview-item:last-child {
            border-bottom: none;
        }

        .preview-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .preview-value {
            color: #34495e;
            text-align: right;
            max-width: 60%;
            word-break: break-word;
        }

        /* Loading Spinner */
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            display: none;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            z-index: 1001;
        }

        .loading-spinner.active {
            display: flex;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #ecf0f1;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modal-content.large {
                width: 95%;
                margin: 1rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .form-group.span-2,
            .form-group.span-3 {
                grid-column: span 1;
            }
            
            .tabs {
                flex-wrap: wrap;
            }
            
            .tab-button {
                flex: 1;
                min-width: auto;
                padding: 0.75rem;
                font-size: 0.9rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }

        /* Estados dinâmicos */
        input[name="data_demissao"]:disabled {
            background: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
        }

        .form-group.highlight {
            animation: highlight 0.5s ease;
        }

        @keyframes highlight {
            0% { background: transparent; }
            50% { background: rgba(102, 126, 234, 0.1); }
            100% { background: transparent; }
        }
    </style>
</head>
<body>
    <!-- Modal -->
    <div class="modal-overlay" id="cadastroModal">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header">
                <h2>📋 Cadastro de Funcionário</h2>
                <button class="close-btn" onclick="fecharModal()" title="Fechar">&times;</button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <!-- Alert Messages -->
                <div class="alert alert-success" id="successAlert"></div>
                <div class="alert alert-danger" id="errorAlert"></div>
                <div class="alert alert-warning" id="warningAlert"></div>

                <!-- Form -->
                <form id="cadastroForm" onsubmit="salvarFuncionario(event)">
                    @csrf

                    <!-- Nome do Funcionario -->
                    <div class="form-group">
                        <label for="nome">Nome do Funcionário *</label>
                        <input 
                            type="text" 
                            id="nome" 
                            name="nome" 
                            placeholder="Ex: Luiz Silva"
                            required
                        >
                    </div>

                    <!-- Tipo e Categoria -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tipo">Setor *</label>
                            <select id="tipo" name="tipo" required>
                                <option value="">Selecione o tipo</option>
                                <option value="capacete">Estoque</option>
                                <option value="luva">Financeiro</option>
                                <option value="bota">Açougue</option>
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
                            <label for="quantidade">CPF *</label>
                            <input 
                                type="string" 
                                id="cpf" 
                                name="cpf" 
                                placeholder="000.000.000-00"
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
                            <label for="data_contrato">Data de Contrato</label>
                            <input 
                                type="date" 
                                id="data_contrato" 
                                name="data_contrato"
                            >
                        </div>

                        <div class="form-group">
                            <label for="data_desligamento">Data de Desligamento</label>
                            <input 
                                type="date" 
                                id="data_desligamento" 
                                name="data_desligamento"
                            >
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="form-group full">
                        <label for="descricao">Descrição</label>
                        <textarea 
                            id="descricao" 
                            name="descricao" 
                            placeholder="Adicione informações adicionais sobre o Funcionário..."
                        ></textarea>
                    </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="fecharModal()">
                    ✕ Cancelar
                </button>
                <button class="btn btn-primary" onclick="enviarFormulario()">
                    <span class="loading-spinner" id="loadingSpinner"></span>
                    💾 Salvar
                </button>
            </div>
        </div>
    </div>

    <script>
        // Abrir Modal
        function abrirModal() {
            const modal = document.getElementById('cadastroModal');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Fechar Modal
        function fecharModal() {
            const modal = document.getElementById('cadastroModal');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            limparFormulario();
        }

        // Limpar Formulário
        function limparFormulario() {
            document.getElementById('cadastroForm').reset();
            document.getElementById('successAlert').classList.remove('active');
            document.getElementById('errorAlert').classList.remove('active');
            document.getElementById('warningAlert').classList.remove('active');
        }

        // Enviar Formulário
        function enviarFormulario() {
            const form = document.getElementById('cadastroForm');
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.add('was-validated');
                mostraAlerta('Por favor, preencha todos os campos obrigatórios.', 'warning');
                return;
            }

            salvarFuncionario(event);
        }

        // Salvar Func
        async function salvarFuncionario(event) {
            event.preventDefault();

            const form = document.getElementById('cadastroForm');
            const formData = new FormData(form);
            const loadingSpinner = document.getElementById('loadingSpinner');

            loadingSpinner.classList.add('active');

            try {
                const response = await fetch('/api/funcionarios', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    mostraAlerta('Funcionário cadastrado com sucesso!', 'success');
                    limparFormulario();
                    
                    setTimeout(() => {
                        fecharModal();
                        location.reload(); // Atualizar página para ver o novo Funcionário
                    }, 2000);
                } else {
                    const erro = data.message || 'Erro ao cadastrar Funcionário';
                    mostraAlerta(erro, 'danger');
                }
            } catch (error) {
                console.error('Erro:', error);
                mostraAlerta('Erro ao processar requisição. Tente novamente.', 'danger');
            } finally {
                loadingSpinner.classList.remove('active');
            }
        }

        // Mostrar Alertas
        function mostraAlerta(mensagem, tipo) {
            const alertId = `${tipo}Alert`;
            const alertElement = document.getElementById(alertId);
            alertElement.textContent = mensagem;
            alertElement.classList.add('active');

            setTimeout(() => {
                alertElement.classList.remove('active');
            }, 5000);
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