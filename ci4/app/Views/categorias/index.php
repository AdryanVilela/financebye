<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-roxo mb-0">Minhas Categorias</h1>
        <button type="button" class="btn btn-roxo shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCategoria">
            <i class="fas fa-plus me-2"></i> Nova Categoria
        </button>
    </div>
    
    <div class="row">
        <!-- Categorias de Receitas -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm hover-card h-100">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold text-success">
                        <i class="fas fa-arrow-up me-2"></i> Receitas
                    </h5>
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2" id="countReceitas">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 ps-4">Nome</th>
                                    <th class="border-0 py-3 text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="categoriasReceitas">
                                <!-- Dados serão carregados via AJAX -->
                                <tr>
                                    <td colspan="2" class="text-center py-5">
                                        <div class="spinner-border spinner-border-sm text-roxo" role="status"></div>
                                        <span class="ms-2">Carregando categorias...</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Categorias de Despesas -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm hover-card h-100">
                <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold text-danger">
                        <i class="fas fa-arrow-down me-2"></i> Despesas
                    </h5>
                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2" id="countDespesas">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 ps-4">Nome</th>
                                    <th class="border-0 py-3 text-end pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="categoriasDespesas">
                                <!-- Dados serão carregados via AJAX -->
                                <tr>
                                    <td colspan="2" class="text-center py-5">
                                        <div class="spinner-border spinner-border-sm text-roxo" role="status"></div>
                                        <span class="ms-2">Carregando categorias...</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nova/Editar Categoria (Modernizado) -->
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold text-roxo" id="modalCategoriaLabel">
                    <i class="fas fa-plus-circle me-2"></i> Nova Categoria
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <form id="categoriaForm">
                    <input type="hidden" id="categoriaId">
                    
                    <!-- Tipo de Categoria (Redesenhado) -->
                    <div class="transaction-type-selector mb-4">
                        <label class="form-label small text-muted fw-medium mb-2">Tipo de Categoria</label>
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
                    
                    <!-- Nome -->
                    <div class="mb-4">
                        <label for="nome" class="form-label small text-muted fw-medium">Nome da Categoria</label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="nome" placeholder="Ex: Alimentação, Transporte, Salário..." required>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-0 p-3">
                <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="button" class="btn btn-roxo fw-medium shadow-sm" id="salvarCategoria">
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
                <p class="mb-4 text-muted">Tem certeza que deseja excluir esta categoria?</p>
                <p class="mb-4 small fw-medium text-danger">Atenção: Excluir uma categoria também excluirá todas as transações vinculadas a ela.</p>
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
<style>
/* Estilos para os seletores de tipo de transação (mesmos da tela de transações) */
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

/* Estilo dos cards */
.hover-card {
    transition: all 0.2s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Estilos para a tabela */
.table td, .table th {
    padding: 1rem 1rem;
}

.table-hover tbody tr {
    transition: all 0.2s;
}

.table-hover tbody tr:hover {
    background-color: rgba(105, 57, 118, 0.03);
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
    margin-left: 0.25rem;
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

/* Estilos para entradas de formulário */
.form-control:focus, .form-select:focus {
    border-color: var(--roxo-light);
    box-shadow: 0 0 0 0.25rem rgba(105, 57, 118, 0.25);
}

.form-control, .form-select {
    background-color: #f9f9f9;
}
</style>

<script>
    let categoriaIdParaExcluir = null;
    
    $(document).ready(function() {
        // Carregar categorias
        loadCategorias();
        
        // Handler para salvar categoria
        $('#salvarCategoria').click(function() {
            // Adicionar loading ao botão
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');
            $btn.prop('disabled', true);
            
            salvarCategoria().then(() => {
                // Restaurar botão após operação
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            }).catch(() => {
                // Restaurar botão em caso de erro
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            });
        });
        
        // Handler para abrir modal de confirmação de exclusão
        $(document).on('click', '.btn-excluir', function() {
            categoriaIdParaExcluir = $(this).data('id');
            $('#modalConfirmacao').modal('show');
        });
        
        // Handler para confirmar exclusão
        $('#confirmarExclusao').click(function() {
            // Adicionar loading ao botão
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...');
            $btn.prop('disabled', true);
            
            excluirCategoria(categoriaIdParaExcluir).finally(() => {
                // Restaurar botão após operação
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            });
        });
        
        // Handler para editar categoria
        $(document).on('click', '.btn-editar', function() {
            const id = $(this).data('id');
            const tipo = $(this).data('tipo');
            const nome = $(this).data('nome');
            
            $('#categoriaId').val(id);
            $('#nome').val(nome);
            
            // Selecionar o tipo atual
            if (tipo === 'receita') {
                $('#tipoReceita').prop('checked', true);
            } else {
                $('#tipoDespesa').prop('checked', true);
            }
            
            // Desabilitar os radio buttons de tipo na edição
            $('input[name="categoriaTipo"]').prop('disabled', true);
            
            // Adicionar classe de desabilitado e mensagem visual
            $('.transaction-type-toggle').addClass('opacity-75');
            $('.transaction-type-selector').append('<div class="small text-muted mt-1"><i class="fas fa-info-circle me-1"></i> O tipo da categoria não pode ser alterado após a criação.</div>');
            
            // Atualizar o toggle visual
            updateTransactionTypeToggle();
            
            $('#modalCategoriaLabel').html('<i class="fas fa-edit me-2"></i> Editar Categoria');
            $('#modalCategoria').modal('show');
        });
        
        // Função para atualizar o toggle de tipo de categoria
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
        
        // Handler para alternar entre tipos de categoria
        $('input[name="categoriaTipo"]').change(function() {
            // Atualizar o seletor visual de acordo com o tipo
            updateTransactionTypeToggle();
        });
        
        // Limpar formulário quando o modal é fechado
        $('#modalCategoria').on('hidden.bs.modal', function() {
            $('#categoriaForm')[0].reset();
            $('#categoriaId').val('');
            $('#modalCategoriaLabel').html('<i class="fas fa-plus-circle me-2"></i> Nova Categoria');
            $('#tipoDespesa').prop('checked', true);
            
            // Reabilitar os radio buttons para criação
            $('input[name="categoriaTipo"]').prop('disabled', false);
            
            // Remover classes de desabilitado e mensagem
            $('.transaction-type-toggle').removeClass('opacity-75');
            $('.transaction-type-selector .small.text-muted').remove();
            
            updateTransactionTypeToggle();
        });
    });
    
    function loadCategorias() {
        // Carregar categorias de receitas
        $.ajax({
            url: '<?= base_url('api/categorias') ?>',
            type: 'GET',
            data: { tipo: 'receita' },
            success: function(response) {
                if (response.status === 'success') {
                    renderCategorias('receita', response.data || []);
                    // Atualizar contador
                    $('#countReceitas').html(response.data ? response.data.length : 0);
                } else {
                    console.error('Erro ao carregar categorias de receitas:', response);
                    $('#categoriasReceitas').html(`
                        <tr>
                            <td colspan="2" class="text-center py-5">
                                <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                Erro ao carregar categorias de receitas.
                            </td>
                        </tr>
                    `);
                    $('#countReceitas').html('0');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
                $('#categoriasReceitas').html(`
                    <tr>
                        <td colspan="2" class="text-center py-5">
                            <i class="fas fa-exclamation-circle text-danger me-2"></i>
                            Erro ao carregar categorias de receitas.
                        </td>
                    </tr>
                `);
                $('#countReceitas').html('0');
            }
        });
        
        // Carregar categorias de despesas
        $.ajax({
            url: '<?= base_url('api/categorias') ?>',
            type: 'GET',
            data: { tipo: 'despesa' },
            success: function(response) {
                if (response.status === 'success') {
                    renderCategorias('despesa', response.data || []);
                    // Atualizar contador
                    $('#countDespesas').html(response.data ? response.data.length : 0);
                } else {
                    console.error('Erro ao carregar categorias de despesas:', response);
                    $('#categoriasDespesas').html(`
                        <tr>
                            <td colspan="2" class="text-center py-5">
                                <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                Erro ao carregar categorias de despesas.
                            </td>
                        </tr>
                    `);
                    $('#countDespesas').html('0');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
                $('#categoriasDespesas').html(`
                    <tr>
                        <td colspan="2" class="text-center py-5">
                            <i class="fas fa-exclamation-circle text-danger me-2"></i>
                            Erro ao carregar categorias de despesas.
                        </td>
                    </tr>
                `);
                $('#countDespesas').html('0');
            }
        });
    }
    
    function renderCategorias(tipo, categorias) {
        const tabela = tipo === 'receita' ? '#categoriasReceitas' : '#categoriasDespesas';
        
        if(categorias.length === 0) {
            $(tabela).html(`
                <tr>
                    <td colspan="2" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="fw-medium">Nenhuma categoria encontrada</h5>
                            <p class="text-muted">Adicione ${tipo === 'receita' ? 'uma receita' : 'uma despesa'} clicando no botão "Nova Categoria".</p>
                        </div>
                    </td>
                </tr>
            `);
            return;
        }
        
        let html = '';
        
        categorias.forEach(function(categoria) {
            html += `
                <tr>
                    <td class="ps-4">${categoria.nome}</td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn-action edit me-2 btn-editar" 
                                    data-id="${categoria.id}" 
                                    data-tipo="${categoria.tipo}" 
                                    data-nome="${categoria.nome}"
                                    title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn-action delete btn-excluir" 
                                    data-id="${categoria.id}"
                                    title="Excluir">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        $(tabela).html(html);
    }
    
    function salvarCategoria() {
        return new Promise((resolve, reject) => {
            // Validar formulário
            const form = document.getElementById('categoriaForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                reject();
                return;
            }
            
            // Obter dados do formulário
            const id = $('#categoriaId').val();
            const data = {
                nome: $('#nome').val(),
                tipo: $('input[name="categoriaTipo"]:checked').val()
            };
            
            // Se for criação (não edição), adicionar empresa_id
            // A empresa_id será obtida da sessão no backend, enviamos apenas um valor qualquer
            if (!id) {
                data.empresa_id = '1'; // Este valor será substituído pelo backend com o ID da empresa do usuário logado
            }
            
            console.log('Enviando dados:', data);
            
            // Determinar se é criação ou atualização
            const url = id ? 
                `<?= base_url('api/categorias') ?>/${id}` : 
                '<?= base_url('api/categorias') ?>';
            const method = id ? 'PUT' : 'POST';
            
            $.ajax({
                url: url,
                type: method,
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(response) {
                    if(response.status === 'success') {
                        $('#modalCategoria').modal('hide');
                        
                        // Mostrar mensagem de sucesso
                        const message = id ? 'Categoria atualizada com sucesso!' : 'Categoria criada com sucesso!';
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Recarregar categorias
                        loadCategorias();
                        resolve();
                    } else {
                        console.error('Erro ao salvar categoria:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: response.message || 'Erro ao salvar categoria.'
                        });
                        reject();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error, xhr.responseText);
                    
                    let errorMsg = 'Erro ao salvar categoria. Tente novamente.';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    
                    // Se o erro for relacionado ao empresa_id, mostrar mensagem específica
                    if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.empresa_id) {
                        errorMsg = 'Erro: Você precisa estar logado em uma empresa para cadastrar categorias.';
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: errorMsg
                    });
                    reject();
                }
            });
        });
    }
    
    function excluirCategoria(id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `<?= base_url('api/categorias') ?>/${id}`,
                type: 'DELETE',
                success: function(response) {
                    if(response.status === 'success') {
                        $('#modalConfirmacao').modal('hide');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: 'Categoria excluída com sucesso!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Recarregar categorias
                        loadCategorias();
                        resolve();
                    } else {
                        console.error('Erro ao excluir categoria:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: response.message || 'Erro ao excluir categoria.'
                        });
                        reject();
                    }
                },
                error: function(xhr, status, error) {
                    $('#modalConfirmacao').modal('hide');
                    console.error('Erro na requisição:', error, xhr.responseText);
                    
                    let errorMsg = 'Erro ao excluir categoria. Tente novamente.';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: errorMsg
                    });
                    reject();
                }
            });
        });
    }
</script>
<?= $this->endSection() ?> 