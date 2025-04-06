<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-roxo mb-0">Minhas Transações</h1>
        <button type="button" class="btn btn-roxo shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTransacao">
            <i class="fas fa-plus me-2"></i> Nova Transação
        </button>
    </div>
    
    <!-- Filtros -->
    <div class="card border-0 shadow-sm hover-card mb-4">
        <div class="card-header bg-white p-4 border-0">
            <h5 class="mb-0 fw-semibold text-roxo">
                <i class="fas fa-filter me-2"></i> Filtros
                <button class="btn btn-sm text-muted float-end" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="false">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </h5>
        </div>
        <div class="collapse show" id="collapseFilters">
            <div class="card-body p-4">
                <form id="filtroForm" class="row g-3">
                    <div class="col-md-3">
                        <label for="filtroTipo" class="form-label small text-muted fw-medium">Tipo de Transação</label>
                        <select class="select2-basic form-select form-select-lg rounded-3 shadow-sm border-0" id="filtroTipo">
                            <option value="">Todos os tipos</option>
                            <option value="receita">Receitas</option>
                            <option value="despesa">Despesas</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filtroCategoria" class="form-label small text-muted fw-medium">Categoria</label>
                        <select class="select2-basic form-select form-select-lg rounded-3 shadow-sm border-0" id="filtroCategoria">
                            <option value="">Todas as categorias</option>
                            <!-- Opções serão carregadas via AJAX -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filtroDataInicio" class="form-label small text-muted fw-medium">Data Início</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light"><i class="fas fa-calendar-alt"></i></span>
                            <input type="text" class="form-control form-control-lg rounded-end shadow-sm border-0 date-input" id="filtroDataInicio" placeholder="Selecionar data">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="filtroDataFim" class="form-label small text-muted fw-medium">Data Fim</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light"><i class="fas fa-calendar-alt"></i></span>
                            <input type="text" class="form-control form-control-lg rounded-end shadow-sm border-0 date-input" id="filtroDataFim" placeholder="Selecionar data">
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-light fw-medium shadow-sm me-2" id="limparFiltro">
                            <i class="fas fa-eraser me-2"></i> Limpar
                        </button>
                        <button type="submit" class="btn btn-roxo fw-medium shadow-sm" id="aplicarFiltro">
                            <i class="fas fa-search me-2"></i> Aplicar Filtros
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Lista de Transações -->
    <div class="card border-0 shadow-sm hover-card">
        <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-roxo">
                <i class="fas fa-list-ul me-2"></i> Transações Recentes
            </h5>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-light shadow-sm me-2" id="recarregarTransacoes">
                    <i class="fas fa-sync-alt me-1"></i> Recarregar
                </button>
                <div class="btn-group shadow-sm">
                    <button type="button" class="btn btn-sm btn-light active" id="viewAll">Todas</button>
                    <button type="button" class="btn btn-sm btn-light" id="viewIncome">Receitas</button>
                    <button type="button" class="btn btn-sm btn-light" id="viewExpense">Despesas</button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 py-3 ps-4">Data</th>
                            <th class="border-0 py-3">Descrição</th>
                            <th class="border-0 py-3">Categoria</th>
                            <th class="border-0 py-3 text-end">Valor</th>
                            <th class="border-0 py-3 text-center pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="transacoesTable">
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="spinner-border spinner-border-sm text-roxo" role="status"></div>
                                <span class="ms-2">Carregando transações...</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <nav aria-label="Paginação de transações" class="p-4 border-top">
                <ul class="pagination justify-content-center mb-0" id="paginacao">
                    <!-- Paginação será gerada via JavaScript -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal Nova/Editar Transação (Modernizado) -->
<div class="modal fade" id="modalTransacao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold text-roxo" id="modalTransacaoLabel">
                    <i class="fas fa-plus-circle me-2"></i> Nova Transação
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <form id="transacaoForm">
                    <input type="hidden" id="transacaoId">
                    
                    <!-- Tipo de Transação (Redesenhado) -->
                    <div class="transaction-type-selector mb-4">
                        <label class="form-label small text-muted fw-medium mb-2">Tipo de Transação</label>
                        <div class="position-relative">
                            <div class="transaction-type-toggle rounded-pill d-flex p-1 shadow-sm bg-light">
                                <div class="transaction-type-option flex-fill position-relative">
                                    <input type="radio" class="btn-check" name="categoriaTipo" id="tipoReceita" value="receita">
                                    <label class="btn rounded-pill w-100 py-2 fw-medium btn-receita" for="tipoReceita">
                                        <i class="fas fa-arrow-up me-2"></i> Receita
                                    </label>
                                </div>
                                <div class="transaction-type-option flex-fill position-relative">
                                    <input type="radio" class="btn-check" name="categoriaTipo" id="tipoDespesa" value="despesa" checked>
                                    <label class="btn rounded-pill w-100 py-2 fw-medium btn-despesa" for="tipoDespesa">
                                        <i class="fas fa-arrow-down me-2"></i> Despesa
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Categoria -->
                    <div class="mb-4">
                        <label for="categoria" class="form-label small text-muted fw-medium">Categoria</label>
                        <select class="select2-modal form-select form-select-lg rounded-3 shadow-sm border-0" id="categoria" required>
                            <option value="">Selecione uma categoria</option>
                            <!-- Opções serão carregadas via AJAX -->
                        </select>
                    </div>
                    
                    <!-- Descrição -->
                    <div class="mb-4">
                        <label for="descricao" class="form-label small text-muted fw-medium">Descrição</label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="descricao" placeholder="Ex: Compra no supermercado" required>
                    </div>
                    
                    <!-- Valor -->
                    <div class="mb-4">
                        <label for="valor" class="form-label small text-muted fw-medium">Valor</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light fw-medium">R$</span>
                            <input type="number" class="form-control form-control-lg rounded-end shadow-sm border-0" id="valor" step="0.01" min="0.01" placeholder="0,00" required>
                        </div>
                    </div>
                    
                    <!-- Data -->
                    <div class="mb-4">
                        <label for="data" class="form-label small text-muted fw-medium">Data</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light"><i class="fas fa-calendar-alt"></i></span>
                            <input type="text" class="form-control form-control-lg rounded-end shadow-sm border-0 date-input" id="data" placeholder="Selecionar data" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-0 p-3">
                <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="button" class="btn btn-roxo fw-medium shadow-sm" id="salvarTransacao">
                    <i class="fas fa-save me-2"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão (Modernizado) -->
<div class="modal fade" id="modalConfirmacao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x"></i>
                </div>
                <h5 class="fw-bold mb-3">Confirmar Exclusão</h5>
                <p class="mb-4 text-muted">Tem certeza que deseja excluir esta transação?</p>
                <p class="mb-4 small fw-medium text-danger">Esta ação não pode ser desfeita.</p>
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-light fw-medium me-2" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger fw-medium" id="confirmarExclusao">
                        <i class="fas fa-trash-alt me-2"></i> Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Select2 CSS e JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<style>
/* Estilos para os seletores de tipo de transação */
.transaction-type-toggle {
    position: relative;
    transition: all 0.3s;
}

.transaction-type-toggle::before {
    content: '';
    position: absolute;
    width: 50%;
    height: 85%;
    top: 7.5%;
    background: white;
    border-radius: 50px;
    z-index: 0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.transaction-type-toggle .btn-check + .btn {
    border: none;
    background: transparent;
    transition: all 0.3s;
    z-index: 1;
    position: relative;
    color: #777;
}

/* Cor verde para receita selecionada */
#tipoReceita:checked + .btn {
    color: #28a745;
    font-weight: 600;
    z-index: 1;
}

/* Cor vermelha para despesa selecionada */
#tipoDespesa:checked + .btn {
    color: #dc3545;
    font-weight: 600;
    z-index: 1;
}

.transaction-type-toggle .btn-receita {
    color: #28a745;
}

.transaction-type-toggle .btn-despesa {
    color: #dc3545;
}

#tipoReceita:checked ~ .transaction-type-toggle::before {
    left: 5px;
}

#tipoDespesa:checked ~ .transaction-type-toggle::before {
    left: calc(50% - 5px);
}

/* Estilos para a tabela de transações */
.table td, .table th {
    padding: 1rem 1rem;
}

.table-hover tbody tr {
    transition: all 0.2s;
}

.table-hover tbody tr:hover {
    background-color: rgba(105, 57, 118, 0.03);
}

/* Estilos para entradas de formulário */
.form-control:focus, .form-select:focus {
    border-color: var(--roxo-light);
    box-shadow: 0 0 0 0.25rem rgba(105, 57, 118, 0.25);
}

.form-control, .form-select {
    background-color: #f9f9f9;
}

/* Estilos para badges de categoria */
.category-badge {
    padding: 0.5rem 0.8rem;
    border-radius: 50px;
    font-weight: 500;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
}

.category-badge i {
    margin-right: 0.4rem;
}

.category-badge.income {
    background-color: rgba(40, 167, 69, 0.15);
    color: #28a745;
}

.category-badge.expense {
    background-color: rgba(220, 53, 69, 0.15);
    color: #dc3545;
}

/* Botões de ação na tabela */
.btn-action {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
    background: #f8f9fa;
    border: none;
    color: #6c757d;
}

.btn-action:hover {
    background: var(--roxo-primary);
    color: white;
}

.btn-action.edit:hover {
    background: #0d6efd;
}

.btn-action.delete:hover {
    background: #dc3545;
}

/* Estilos para o Select2 personalizado */
.select2-container--bootstrap-5 .select2-selection {
    background-color: #f9f9f9;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    min-height: 48px;
    padding: 0.5rem 1rem;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding: 0.25rem 0;
    color: #333;
    font-weight: 500;
}

.select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
    background-color: var(--roxo-primary);
}

.select2-container--bootstrap-5 .select2-results__option {
    padding: 0.75rem 1rem;
}

.select2-container--bootstrap-5 .select2-dropdown {
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border-radius: 0.5rem;
}

.select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
}

.select2-container--bootstrap-5 .select2-selection__arrow {
    height: 48px;
}

/* Select2 no modal - evita problemas de z-index */
.modal .select2-container {
    z-index: 1056;
}

.select2-optgroup-label {
    font-weight: 600;
    color: var(--roxo-primary);
    padding: 0.5rem 1rem !important;
    font-size: 0.9rem;
}

/* Add custom icons to optgroups */
.select2-results__option.select2-results__option--group {
    padding-left: 2.5rem;
    position: relative;
}

.select2-results__option.select2-results__option--group::before {
    font-family: "Font Awesome 5 Free";
    position: absolute;
    left: 1rem;
}

.receita-option::before {
    content: "\f062";
    color: #28a745;
}

.despesa-option::before {
    content: "\f063";
    color: #dc3545;
}
</style>

<script>
    let transacoesData = [];
    let currentPage = 1;
    let itemsPerPage = 10;
    let transacaoIdParaExcluir = null;
    let currentView = 'all'; // Tipo de visualização: 'all', 'income', 'expense'
    
    $(document).ready(function() {
        // Inicializar Select2
        initializeSelect2();
        
        // Inicializar Flatpickr
        initFlatpickr();
        
        // Carregar categorias
        loadCategorias();
        
        // Carregar transações
        loadTransacoes();
        
        // Handler para alternar entre tipos de categoria
        $('input[name="categoriaTipo"]').change(function() {
            loadCategoriasByTipo($(this).val());
            
            // Atualizar o seletor visual de acordo com o tipo
            updateTransactionTypeToggle();
        });
        
        // Função para inicializar o Select2
        function initializeSelect2() {
            // Select2 para filtros
            $('.select2-basic').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Selecione uma opção',
                allowClear: true,
                dropdownParent: $('#collapseFilters'),
                language: {
                    noResults: function() {
                        return "Nenhum resultado encontrado";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });
            
            // Select2 para o modal
            $('#categoria').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Selecione uma categoria',
                allowClear: true,
                dropdownParent: $('#modalTransacao .modal-body'),
                language: {
                    noResults: function() {
                        return "Nenhum resultado encontrado";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                },
                templateResult: formatCategoriaOption
            });
            
            // Reset selects quando o modal for fechado
            $('#modalTransacao').on('hidden.bs.modal', function() {
                $('#categoria').val(null).trigger('change');
            });
        }
        
        // Função para formatar opções de categoria com ícones
        function formatCategoriaOption(categoria) {
            if (!categoria.id) return categoria.text;
            
            let $option = $('<span></span>');
            let icon = '';
            
            // Verificar o tipo da categoria pelos dados personalizados ou pela classe do elemento optgroup
            let tipo = $(categoria.element).data('tipo');
            if (!tipo) {
                let $optgroup = $(categoria.element).closest('optgroup');
                if ($optgroup.length) {
                    tipo = $optgroup.attr('label').toLowerCase().includes('receita') ? 'receita' : 'despesa';
                }
            }
            
            // Definir ícone com base no tipo
            if (tipo === 'receita') {
                icon = '<i class="fas fa-arrow-up text-success me-2"></i>';
            } else if (tipo === 'despesa') {
                icon = '<i class="fas fa-arrow-down text-danger me-2"></i>';
            }
            
            $option.html(icon + categoria.text);
            return $option;
        }
        
        // Função para atualizar o toggle de tipo de transação
        function updateTransactionTypeToggle() {
            if ($('#tipoReceita').is(':checked')) {
                $('.transaction-type-toggle::before').css('left', '5px');
                $('.transaction-type-option:first-child .btn').addClass('text-success').removeClass('text-danger');
                $('.transaction-type-option:last-child .btn').removeClass('text-danger');
            } else {
                $('.transaction-type-toggle::before').css('left', 'calc(50% - 5px)');
                $('.transaction-type-option:last-child .btn').addClass('text-danger').removeClass('text-success');
                $('.transaction-type-option:first-child .btn').removeClass('text-success');
            }
        }
        
        // Inicializar o toggle
        updateTransactionTypeToggle();
        
        // Handler para salvar transação
        $('#salvarTransacao').click(function() {
            // Adicionar loading ao botão
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');
            $btn.prop('disabled', true);
            
            salvarTransacao().then(() => {
                // Restaurar botão após operação
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            }).catch(() => {
                // Restaurar botão em caso de erro
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            });
        });
        
        // Handler para filtrar transações
        $('#aplicarFiltro').click(function(e) {
            e.preventDefault();
            // Adicionar loading ao botão
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Aplicando...');
            $btn.prop('disabled', true);
            
            // Converter datas do filtro para o formato esperado pela API
            function converterDataParaAPI(dataStr) {
                if (!dataStr) return '';
                // Converter de DD/MM/YYYY para YYYY-MM-DD
                if (dataStr.includes('/')) {
                    const parts = dataStr.split('/');
                    if (parts.length === 3) {
                        return `${parts[2]}-${parts[1]}-${parts[0]}`;
                    }
                }
                return dataStr;
            }
            
            // Obter e converter datas
            const dataInicio = converterDataParaAPI($('#filtroDataInicio').val());
            const dataFim = converterDataParaAPI($('#filtroDataFim').val());
            
            // Armazenar temporariamente para uso na função loadTransacoes
            window.filtroDataInicio = dataInicio;
            window.filtroDataFim = dataFim;
            
            // Log para depuração
            console.log("Aplicando filtros - dataInicio:", dataInicio, "dataFim:", dataFim);
            
            // Resetar para página 1 ao aplicar filtros
            currentPage = 1;
            
            // Carregar transações com os novos filtros
            loadTransacoes().finally(() => {
                // Restaurar botão após operação
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            });
        });
        
        // Handler para limpar filtros
        $('#limparFiltro').click(function() {
            $('#filtroForm')[0].reset();
            currentPage = 1;
            loadTransacoes();
        });
        
        // Handler para abrir modal de confirmação de exclusão
        $(document).on('click', '.btn-excluir', function() {
            transacaoIdParaExcluir = $(this).data('id');
            $('#modalConfirmacao').modal('show');
        });
        
        // Handler para confirmar exclusão
        $('#confirmarExclusao').click(function() {
            excluirTransacao(transacaoIdParaExcluir);
        });
        
        // Handler para editar transação (corrigir seletor)
        $(document).on('click', '.btn-action.edit', function() {
            const id = $(this).data('id');
            editarTransacao(id);
        });
        
        // Handler para alternar visualização
        $('#viewAll').click(function() {
            $('.btn-group .btn').removeClass('active');
            $(this).addClass('active');
            currentView = 'all';
            filterTransactionsByView();
        });
        
        $('#viewIncome').click(function() {
            $('.btn-group .btn').removeClass('active');
            $(this).addClass('active');
            currentView = 'income';
            filterTransactionsByView();
        });
        
        $('#viewExpense').click(function() {
            $('.btn-group .btn').removeClass('active');
            $(this).addClass('active');
            currentView = 'expense';
            filterTransactionsByView();
        });
        
        // Modal de nova transação
        $('#modalTransacao').on('show.bs.modal', function (e) {
            if (!e.relatedTarget) return; // Não redefinir quando for para editar
            
            // Limpar formulário quando abrir para nova transação
            $('#transacaoForm')[0].reset();
            $('#transacaoId').val('');
            $('#modalTransacaoLabel').html('<i class="fas fa-plus-circle me-2"></i> Nova Transação');
            
            // Definir data atual usando flatpickr
            setTimeout(() => {
                const dataPicker = document.querySelector("#data")._flatpickr;
                if (dataPicker) {
                    dataPicker.setDate(new Date());
                    // Forçar redimensionamento para exibir todos os dias
                    dataPicker.resize();
                }
            }, 100);
            
            // Carregar categorias do tipo despesa (padrão)
            loadCategoriasByTipo('despesa');
            
            // Atualizar o seletor visual para despesa
            updateTransactionTypeToggle();
        });
        
        // Handler para recarregar transações
        $('#recarregarTransacoes').click(function() {
            // Adicionar loading ao botão
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Carregando...');
            $btn.prop('disabled', true);
            
            loadTransacoes().finally(() => {
                // Restaurar botão após operação
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            });
        });
    });
    
    function loadCategorias() {
        $.ajax({
            url: '<?= base_url('api/categorias') ?>',
            type: 'GET',
            success: function(response) {
                if(response.status === 'success') {
                    // Preencher select de filtro de categorias
                    let options = '<option value="">Todas as categorias</option>';
                    
                    if(response.data) {
                        const categoriasPorTipo = {
                            receita: [],
                            despesa: []
                        };
                        
                        // Agrupar categorias por tipo
                        response.data.forEach(function(categoria) {
                            if(categoria.tipo) {
                                categoriasPorTipo[categoria.tipo].push(categoria);
                            }
                        });
                        
                        // Criar opções agrupadas
                        if(categoriasPorTipo.receita.length > 0) {
                            options += '<optgroup label="Receitas">';
                            categoriasPorTipo.receita.forEach(function(categoria) {
                                options += `<option value="${categoria.id}" data-tipo="receita">${categoria.nome}</option>`;
                            });
                            options += '</optgroup>';
                        }
                        
                        if(categoriasPorTipo.despesa.length > 0) {
                            options += '<optgroup label="Despesas">';
                            categoriasPorTipo.despesa.forEach(function(categoria) {
                                options += `<option value="${categoria.id}" data-tipo="despesa">${categoria.nome}</option>`;
                            });
                            options += '</optgroup>';
                        }
                    }
                    
                    $('#filtroCategoria').html(options).trigger('change');
                    
                    // Carregar categorias iniciais no modal (despesa por padrão)
                    loadCategoriasByTipo('despesa');
                }
            }
        });
    }
    
    function loadCategoriasByTipo(tipo) {
        $.ajax({
            url: '<?= base_url('api/categorias') ?>',
            type: 'GET',
            data: {
                tipo: tipo
            },
            success: function(response) {
                if(response.status === 'success') {
                    let options = '<option value="">Selecione uma categoria</option>';
                    
                    if(response.data) {
                        response.data.forEach(function(categoria) {
                            options += `<option value="${categoria.id}" data-tipo="${tipo}">${categoria.nome}</option>`;
                        });
                    }
                    
                    $('#categoria').html(options).trigger('change');
                }
            }
        });
    }
    
    function loadTransacoes() {
        return new Promise((resolve, reject) => {
            // Log para depuração
            console.log("Iniciando carregamento de transações");
            
            // Exibir loading
            $('#transacoesTable').html(`
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="spinner-border spinner-border-sm text-roxo" role="status"></div>
                        <span class="ms-2">Carregando transações...</span>
                    </td>
                </tr>
            `);
            
            // Obter parâmetros de filtro
            const filtroTipo = $('#filtroTipo').val();
            const filtroCategoria = $('#filtroCategoria').val();
            
            // Processar datas do filtro para o formato esperado pela API (YYYY-MM-DD)
            let filtroDataInicio = window.filtroDataInicio || '';
            let filtroDataFim = window.filtroDataFim || '';
            
            // Se não tiver sido processado anteriormente, converter da interface (DD/MM/YYYY)
            if (!filtroDataInicio && $('#filtroDataInicio').val()) {
                filtroDataInicio = converterDataParaAPI($('#filtroDataInicio').val());
            }
            
            if (!filtroDataFim && $('#filtroDataFim').val()) {
                filtroDataFim = converterDataParaAPI($('#filtroDataFim').val());
            }
            
            // Função auxiliar para converter datas
            function converterDataParaAPI(dataStr) {
                if (!dataStr) return '';
                // Converter de DD/MM/YYYY para YYYY-MM-DD
                if (dataStr.includes('/')) {
                    const parts = dataStr.split('/');
                    if (parts.length === 3) {
                        return `${parts[2]}-${parts[1]}-${parts[0]}`;
                    }
                }
                return dataStr;
            }
            
            // Log para depuração
            console.log("Filtros:", { 
                tipo: filtroTipo, 
                categoria: filtroCategoria, 
                dataInicio: filtroDataInicio, 
                dataFim: filtroDataFim 
            });
            
            // Limpar as variáveis temporárias
            window.filtroDataInicio = null;
            window.filtroDataFim = null;
            
            // Montar query string para a requisição
            let queryParams = [];
            if (filtroTipo) queryParams.push(`tipo=${encodeURIComponent(filtroTipo)}`);
            if (filtroCategoria) queryParams.push(`categoria_id=${encodeURIComponent(filtroCategoria)}`);
            if (filtroDataInicio) queryParams.push(`data_inicio=${encodeURIComponent(filtroDataInicio)}`);
            if (filtroDataFim) queryParams.push(`data_fim=${encodeURIComponent(filtroDataFim)}`);
            
            const queryString = queryParams.length > 0 ? `?${queryParams.join('&')}` : '';
            const url = `<?= base_url('api/transacoes') ?>${queryString}`;
            
            // Log para depuração
            console.log("Chamando API:", url);
            
            $.ajax({
                url: url,
                type: 'GET',
                timeout: 15000, // Timeout de 15 segundos
                success: function(response) {
                    // Log para depuração
                    console.log("Resposta da API:", response);
                    
                    if(response.status === 'success') {
                        transacoesData = response.data || [];
                        renderTransacoes();
                        resolve();
                    } else {
                        console.error('Erro ao carregar transações:', response);
                        $('#transacoesTable').html(`
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                    Erro ao carregar transações. Tente novamente.
                                </td>
                            </tr>
                        `);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: response.message || 'Erro ao carregar transações.'
                        });
                        reject();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error, 'Status:', status, 'Detalhes:', xhr);
                    $('#transacoesTable').html(`
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                Erro ao carregar transações. <button type="button" class="btn btn-link p-0" id="btnTentarNovamente">Tentar novamente</button>
                            </td>
                        </tr>
                    `);
                    
                    // Adicionar handler para o botão de tentar novamente
                    $('#btnTentarNovamente').click(function() {
                        loadTransacoes();
                    });
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Erro ao carregar transações. Tente novamente.'
                    });
                    reject();
                }
            });
        });
    }
    
    function filterTransactionsByView() {
        renderTransacoes();
    }
    
    function renderTransacoes() {
        // Filtrar transações conforme visualização atual
        let filteredData = [...transacoesData];
        
        if (currentView === 'income') {
            filteredData = filteredData.filter(t => t.categoria_tipo === 'receita');
        } else if (currentView === 'expense') {
            filteredData = filteredData.filter(t => t.categoria_tipo === 'despesa');
        }
        
        // Calcular paginação
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedData = filteredData.slice(startIndex, endIndex);
        
        // Gerar HTML da tabela
        let html = '';
        
        if(paginatedData.length === 0) {
            html = `
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="fw-medium">Nenhuma transação encontrada</h5>
                            <p class="text-muted">Tente ajustar os filtros ou adicione uma nova transação.</p>
                        </div>
                    </td>
                </tr>
            `;
        } else {
            paginatedData.forEach(function(transaction) {
                // Determinar classe e ícone com base no tipo
                let valueClass = transaction.categoria_tipo === 'receita' ? 'text-success' : 'text-danger';
                let valueSign = transaction.categoria_tipo === 'receita' ? '+' : '-';
                let categoryClass = transaction.categoria_tipo === 'receita' ? 'income' : 'expense';
                let categoryIcon = transaction.categoria_tipo === 'receita' ? 'fa-arrow-up' : 'fa-arrow-down';
                
                html += `
                    <tr>
                        <td class="ps-4">${formatDate(transaction.data)}</td>
                        <td>${transaction.descricao}</td>
                        <td>
                            <span class="category-badge ${categoryClass}">
                                <i class="fas ${categoryIcon}"></i>${transaction.categoria_nome}
                            </span>
                        </td>
                        <td class="text-end fw-medium ${valueClass}">${valueSign} R$ ${formatCurrency(Math.abs(transaction.valor))}</td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn-action edit me-2" data-id="${transaction.id}" title="Editar">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" class="btn-action delete btn-excluir" data-id="${transaction.id}" title="Excluir">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }
        
        $('#transacoesTable').html(html);
        
        // Gerar paginação
        let paginacaoHtml = '';
        
        if(totalPages > 1) {
            paginacaoHtml += `
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            `;
            
            for(let i = 1; i <= totalPages; i++) {
                paginacaoHtml += `
                    <li class="page-item ${currentPage === i ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
            }
            
            paginacaoHtml += `
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Próximo">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            `;
        }
        
        $('#paginacao').html(paginacaoHtml);
        
        // Handler para alterar página
        $('.page-link').click(function(e) {
            e.preventDefault();
            currentPage = parseInt($(this).data('page'));
            renderTransacoes();
            
            // Scroll para o topo da tabela
            $('html, body').animate({
                scrollTop: $('.table').offset().top - 100
            }, 200);
        });
    }
    
    function salvarTransacao() {
        return new Promise((resolve, reject) => {
            // Validar formulário
            const form = document.getElementById('transacaoForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                reject();
                return;
            }
            
            // Capturar dados do formulário
            const id = $('#transacaoId').val();
            
            // Obter data do flatpickr e converter para o formato YYYY-MM-DD
            let dataValue = $('#data').val();
            // Converter de DD/MM/YYYY para YYYY-MM-DD
            if (dataValue && dataValue.includes('/')) {
                const parts = dataValue.split('/');
                if (parts.length === 3) {
                    dataValue = `${parts[2]}-${parts[1]}-${parts[0]}`;
                }
            }
            
            const data = {
                categoria_id: $('#categoria').val(),
                descricao: $('#descricao').val(),
                valor: $('#valor').val(),
                data: dataValue
            };
            
            console.log('Enviando dados da transação:', data); // Para debug
            
            // URL e método dependem se é edição ou criação
            const url = id ? 
                `<?= base_url('api/transacoes') ?>/${id}` : 
                '<?= base_url('api/transacoes') ?>';
            const method = id ? 'PUT' : 'POST';
            
            $.ajax({
                url: url,
                type: method,
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    if(response.status === 'success') {
                        // Fechar modal
                        $('#modalTransacao').modal('hide');
                        
                        // Recarregar transações
                        loadTransacoes();
                        
                        // Mostrar mensagem de sucesso
                        const message = id ? 'Transação atualizada com sucesso!' : 'Transação criada com sucesso!';
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        resolve();
                    } else {
                        console.error('Erro ao salvar transação:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: response.message || 'Erro ao salvar transação.'
                        });
                        reject();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Erro ao salvar transação. Tente novamente.'
                    });
                    reject();
                }
            });
        });
    }
    
    function editarTransacao(id) {
        // Buscar transação específica
        const transacao = transacoesData.find(t => t.id == id);
        
        if (!transacao) {
            console.error('Transação não encontrada:', id);
            return;
        }
        
        // Preencher formulário
        $('#transacaoId').val(transacao.id);
        $('#modalTransacaoLabel').html('<i class="fas fa-edit me-2"></i> Editar Transação');
        
        // Selecionar tipo e carregar categorias
        $(`#tipo${transacao.categoria_tipo.charAt(0).toUpperCase() + transacao.categoria_tipo.slice(1)}`).prop('checked', true);
        loadCategoriasByTipo(transacao.categoria_tipo);
        
        // Atualizar o toggle visual
        if (transacao.categoria_tipo === 'receita') {
            $('.transaction-type-option:first-child .btn').addClass('text-success').removeClass('text-danger');
            $('.transaction-type-option:last-child .btn').removeClass('text-danger');
        } else {
            $('.transaction-type-option:last-child .btn').addClass('text-danger').removeClass('text-success');
            $('.transaction-type-option:first-child .btn').removeClass('text-success');
        }
        
        // Definir outros campos (com timeout para garantir que as categorias foram carregadas)
        setTimeout(() => {
            $('#categoria').val(transacao.categoria_id).trigger('change');
            $('#descricao').val(transacao.descricao);
            $('#valor').val(Math.abs(transacao.valor));
            
            // Formatar a data para o Flatpickr (DD/MM/YYYY)
            const dataFormatada = formatDate(transacao.data);
            const dataPicker = document.querySelector("#data")._flatpickr;
            dataPicker.setDate(dataFormatada);
            
            // Abrir modal
            $('#modalTransacao').modal('show');
        }, 500);
    }
    
    function excluirTransacao(id) {
        // Adicionar loading ao botão
        const $btn = $('#confirmarExclusao');
        const originalHtml = $btn.html();
        $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...');
        $btn.prop('disabled', true);
        
        $.ajax({
            url: `<?= base_url('api/transacoes') ?>/${id}`,
            type: 'DELETE',
            success: function(response) {
                // Fechar modal
                $('#modalConfirmacao').modal('hide');
                
                // Recarregar transações
                loadTransacoes();
                
                // Mostrar mensagem de sucesso
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: 'Transação excluída com sucesso!',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Restaurar botão
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Erro ao excluir transação. Tente novamente.'
                });
                
                // Restaurar botão
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            }
        });
    }
    
    function formatCurrency(value) {
        return parseFloat(value).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
    
    function formatDate(dateString) {
        // Corrigindo problema de fuso horário que estava causando diferença de 1 dia
        // Usar split e construir a data manualmente para evitar conversão de fuso horário
        const parts = dateString.split('-');
        if (parts.length === 3) {
            // Formato ano-mes-dia vindo do banco (2025-04-05)
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        } else {
            // Fallback para o método anterior caso o formato não seja o esperado
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        }
    }

    // Adicionar classe personalizada para melhorar a aparência do título do mês
    function customizeCalendarTitle(instance) {
        if (instance && instance.calendarContainer) {
            // Adicionar classe específica para nosso estilo
            instance.calendarContainer.classList.add('custom-calendar');
            
            // Garantir que o mês fique visível e centralizado
            const monthElement = instance.calendarContainer.querySelector('.flatpickr-current-month');
            if (monthElement) {
                monthElement.style.display = 'flex';
                monthElement.style.justifyContent = 'center';
                monthElement.style.width = '100%';
                
                // Centralizar o texto
                const monthText = monthElement.querySelector('.cur-month');
                if (monthText) {
                    monthText.style.textAlign = 'center';
                    monthText.style.width = '100%';
                }
            }
        }
    }

    // Inicializar todos os datepickers quando o documento estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar os campos de data de todas as seções
        const datePickers = document.querySelectorAll('.date-input');
        datePickers.forEach(function(element) {
            initFlatpickr(element);
        });
    });

    // Função para inicializar o flatpickr de forma padronizada
    function initFlatpickr(selector) {
        // Verificar se o seletor existe antes de inicializar
        if (!selector) {
            console.warn("Tentativa de inicializar Flatpickr com seletor inválido", selector);
            return null;
        }
        
        // Se for uma string (seletor), verificar se existe algum elemento correspondente
        if (typeof selector === 'string') {
            const elements = document.querySelectorAll(selector);
            if (elements.length === 0) {
                console.warn("Nenhum elemento encontrado para o seletor Flatpickr:", selector);
                return null;
            }
        }
        
        // Configuração básica
        const config = {
            dateFormat: "d/m/Y",
            locale: "pt",
            disableMobile: true,
            allowInput: true,
            static: false,
            prevArrow: '<i class="fas fa-chevron-left"></i>',
            nextArrow: '<i class="fas fa-chevron-right"></i>',
            
            // Função para abrir o flatpickr em modo modal
            onOpen: function(selectedDates, dateStr, instance) {
                // Adicionar classe modal ao calendario
                instance.calendarContainer.classList.add('flatpickr-modal');
                
                // Criar overlay
                const overlay = document.createElement('div');
                overlay.className = 'flatpickr-overlay';
                overlay.id = 'flatpickr-overlay-' + Math.random().toString(36).substring(2, 9);
                document.body.appendChild(overlay);
                
                // Associar o overlay a esta instância
                instance._overlay = overlay;
                
                // Adicionar botão de fechar
                if (!instance.calendarContainer.querySelector('.flatpickr-close-button')) {
                    const closeButton = document.createElement('button');
                    closeButton.className = 'flatpickr-close-button';
                    closeButton.innerHTML = '<i class="fas fa-times"></i>';
                    closeButton.addEventListener('click', function() {
                        instance.close();
                    });
                    instance.calendarContainer.appendChild(closeButton);
                }
                
                // Adicionar evento de clique ao overlay para fechar o calendário
                overlay.addEventListener('click', function() {
                    instance.close();
                });
                
                // Personalizar o título do mês
                customizeCalendarTitle(instance);
            },
            
            // Função para limpar o overlay ao fechar
            onClose: function(selectedDates, dateStr, instance) {
                // Remover o overlay
                if (instance._overlay) {
                    instance._overlay.remove();
                    delete instance._overlay;
                }
            }
        };
        
        // Inicializar o flatpickr
        try {
            return flatpickr(selector, config);
        } catch (error) {
            console.error("Erro ao inicializar Flatpickr:", error);
            return null;
        }
    }
</script>
<?= $this->endSection() ?> 