<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-roxo mb-0">Usuários do Sistema</h1>
        <button type="button" class="btn btn-roxo shadow-sm" data-bs-toggle="modal" data-bs-target="#modalUsuario">
            <i class="fas fa-user-plus me-2"></i> Novo Usuário
        </button>
    </div>
    
    <div class="card border-0 shadow-sm hover-card">
        <div class="card-header bg-white p-4 border-0">
            <h5 class="mb-0 fw-semibold text-roxo">
                <i class="fas fa-users me-2"></i> Lista de Usuários
                <span class="badge bg-roxo-light text-roxo rounded-pill ms-2 px-3 py-2" id="countUsuarios">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                </span>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 py-3 ps-4">Nome</th>
                            <th class="border-0 py-3">E-mail</th>
                            <th class="border-0 py-3">Status</th>
                            <th class="border-0 py-3 text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="usuariosTable">
                        <!-- Dados serão carregados via AJAX -->
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="spinner-border spinner-border-sm text-roxo" role="status"></div>
                                <span class="ms-2">Carregando usuários...</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Novo/Editar Usuário (Modernizado) -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold text-roxo" id="modalUsuarioLabel">
                    <i class="fas fa-user-plus me-2"></i> Novo Usuário
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <form id="usuarioForm">
                    <input type="hidden" id="usuarioId">
                    
                    <!-- Nome -->
                    <div class="mb-4">
                        <label for="nome" class="form-label small text-muted fw-medium">Nome Completo</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control form-control-lg rounded-end shadow-sm border-0" id="nome" placeholder="Digite o nome completo" required>
                        </div>
                    </div>
                    
                    <!-- E-mail -->
                    <div class="mb-4">
                        <label for="email" class="form-label small text-muted fw-medium">E-mail</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control form-control-lg rounded-end shadow-sm border-0" id="email" placeholder="exemplo@empresa.com" required>
                        </div>
                    </div>
                    
                    <!-- Senha -->
                    <div class="mb-4 senha-container">
                        <label for="senha" class="form-label small text-muted fw-medium">Senha</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control form-control-lg rounded-end shadow-sm border-0" id="senha" placeholder="Digite a senha">
                        </div>
                        <small class="form-text text-muted mt-2" id="senhaHelp"><i class="fas fa-info-circle me-1"></i> Mínimo de 6 caracteres.</small>
                    </div>
                    
                    <!-- Confirmar Senha -->
                    <div class="mb-4 senha-container">
                        <label for="confirmarSenha" class="form-label small text-muted fw-medium">Confirmar Senha</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control form-control-lg rounded-end shadow-sm border-0" id="confirmarSenha" placeholder="Confirme a senha">
                        </div>
                    </div>
                    
                    <!-- Status Ativo/Inativo -->
                    <div class="mb-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="ativo" checked>
                            <label class="form-check-label fw-medium" for="ativo">
                                <span class="text-success">Usuário Ativo</span>
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="fas fa-info-circle me-1"></i> Usuários inativos não podem acessar o sistema.
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-0 p-3">
                <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="button" class="btn btn-roxo fw-medium shadow-sm" id="salvarUsuario">
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
                <p class="mb-4 text-muted">Tem certeza que deseja excluir este usuário?</p>
                <p class="mb-4 small fw-medium text-danger">
                    <i class="fas fa-exclamation-circle me-1"></i> Esta ação não pode ser desfeita.
                </p>
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

/* Status badges */
.status-badge {
    padding: 0.5rem 0.8rem;
    border-radius: 50px;
    font-weight: 500;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
}

.status-badge i {
    margin-right: 0.4rem;
}

.status-badge.active {
    background-color: rgba(40, 167, 69, 0.15);
    color: #28a745;
}

.status-badge.inactive {
    background-color: rgba(220, 53, 69, 0.15);
    color: #dc3545;
}

/* Switch de status */
.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

.form-check-input {
    width: 3em;
    height: 1.5em;
    margin-top: 0.25em;
}

.form-check-label {
    padding-left: 0.5rem;
}

/* Quando o switch mudar para unchecked */
#ativo:not(:checked) + .form-check-label span {
    color: #dc3545;
}

#ativo:not(:checked) + .form-check-label span:before {
    content: 'Usuário Inativo';
}

#ativo:checked + .form-check-label span:before {
    content: 'Usuário Ativo';
}
</style>

<script>
    let usuarioIdParaExcluir = null;
    
    $(document).ready(function() {
        // Carregar usuários
        loadUsuarios();
        
        // Handler para salvar usuário
        $('#salvarUsuario').click(function() {
            // Adicionar loading ao botão
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');
            $btn.prop('disabled', true);
            
            salvarUsuario().finally(() => {
                // Restaurar botão após operação
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            });
        });
        
        // Handler para abrir modal de confirmação de exclusão
        $(document).on('click', '.btn-excluir', function() {
            usuarioIdParaExcluir = $(this).data('id');
            $('#modalConfirmacao').modal('show');
        });
        
        // Handler para confirmar exclusão
        $('#confirmarExclusao').click(function() {
            // Adicionar loading ao botão
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Excluindo...');
            $btn.prop('disabled', true);
            
            excluirUsuario(usuarioIdParaExcluir).finally(() => {
                // Restaurar botão após operação
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            });
        });
        
        // Handler para editar usuário
        $(document).on('click', '.btn-editar', function() {
            const id = $(this).data('id');
            
            // Adicionar um estado de loading ao modal antes de abrí-lo
            $('#modalUsuarioLabel').html('<i class="fas fa-spinner fa-spin me-2"></i> Carregando dados...');
            $('#modalUsuario').modal('show');
            
            // Buscar dados do usuário
            $.ajax({
                url: `<?= base_url('api/usuarios') ?>/${id}`,
                type: 'GET',
                success: function(response) {
                    if(response.status === 'success') {
                        preencherFormularioEdicao(response.data);
                    } else {
                        $('#modalUsuario').modal('hide');
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Não foi possível carregar os dados do usuário.'
                        });
                    }
                },
                error: function(xhr) {
                    $('#modalUsuario').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Não foi possível carregar os dados do usuário.'
                    });
                }
            });
        });
        
        // Atualiza o texto do label conforme status
        $('#ativo').change(function() {
            updateStatusLabel();
        });
        
        function updateStatusLabel() {
            if ($('#ativo').is(':checked')) {
                $('.form-check-label span').removeClass('text-danger').addClass('text-success');
            } else {
                $('.form-check-label span').removeClass('text-success').addClass('text-danger');
            }
        }
        
        // Limpar formulário quando o modal é fechado
        $('#modalUsuario').on('hidden.bs.modal', function() {
            $('#usuarioForm')[0].reset();
            $('#usuarioId').val('');
            $('#modalUsuarioLabel').html('<i class="fas fa-user-plus me-2"></i> Novo Usuário');
            $('.senha-container').show();
            $('#senhaHelp').show();
            $('#senha').prop('required', true);
            $('#confirmarSenha').prop('required', true);
            $('#ativo').prop('checked', true);
            updateStatusLabel();
        });
    });
    
    function loadUsuarios() {
        $.ajax({
            url: '<?= base_url('api/usuarios') ?>',
            type: 'GET',
            success: function(response) {
                if(response.status === 'success') {
                    renderUsuarios(response.data);
                    // Atualizar contador
                    $('#countUsuarios').html(response.data.length || 0);
                } else {
                    console.error('Erro ao carregar usuários:', response);
                    $('#usuariosTable').html(`
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <i class="fas fa-exclamation-circle text-danger me-2"></i>
                                Erro ao carregar usuários. Tente novamente.
                            </td>
                        </tr>
                    `);
                    $('#countUsuarios').html('0');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
                $('#usuariosTable').html(`
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="fas fa-exclamation-circle text-danger me-2"></i>
                            Erro ao carregar usuários. Tente novamente.
                        </td>
                    </tr>
                `);
                $('#countUsuarios').html('0');
            }
        });
    }
    
    function renderUsuarios(usuarios) {
        if(usuarios.length === 0) {
            $('#usuariosTable').html(`
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                            <h5 class="fw-medium">Nenhum usuário encontrado</h5>
                            <p class="text-muted">Adicione um novo usuário clicando no botão "Novo Usuário".</p>
                        </div>
                    </td>
                </tr>
            `);
            return;
        }
        
        let html = '';
        
        usuarios.forEach(function(usuario) {
            // Determinar o status do usuário
            const statusClass = usuario.ativo == 1 ? 'active' : 'inactive';
            const statusText = usuario.ativo == 1 ? 'Ativo' : 'Inativo';
            const statusIcon = usuario.ativo == 1 ? 'fa-check-circle' : 'fa-times-circle';
            
            html += `
                <tr>
                    <td class="ps-4">${usuario.nome}</td>
                    <td>${usuario.email}</td>
                    <td>
                        <span class="status-badge ${statusClass}">
                            <i class="fas ${statusIcon}"></i>${statusText}
                        </span>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn-action edit me-2 btn-editar" data-id="${usuario.id}" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn-action delete btn-excluir" data-id="${usuario.id}" title="Excluir">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        $('#usuariosTable').html(html);
    }
    
    function preencherFormularioEdicao(usuario) {
        $('#usuarioId').val(usuario.id);
        $('#nome').val(usuario.nome);
        $('#email').val(usuario.email);
        $('#ativo').prop('checked', usuario.ativo == 1);
        
        // Atualizar label de status
        if (usuario.ativo == 1) {
            $('.form-check-label span').removeClass('text-danger').addClass('text-success');
        } else {
            $('.form-check-label span').removeClass('text-success').addClass('text-danger');
        }
        
        // Esconder campos de senha na edição (a senha só será atualizada se for preenchida)
        $('.senha-container').hide();
        $('#senhaHelp').hide();
        $('#senha').prop('required', false);
        $('#confirmarSenha').prop('required', false);
        
        $('#modalUsuarioLabel').html('<i class="fas fa-user-edit me-2"></i> Editar Usuário');
    }
    
    function salvarUsuario() {
        return new Promise((resolve, reject) => {
            // Validar formulário
            const form = document.getElementById('usuarioForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                reject();
                return;
            }
            
            // Validar confirmação de senha
            const senha = $('#senha').val();
            const confirmarSenha = $('#confirmarSenha').val();
            
            if (senha && senha !== confirmarSenha) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'As senhas não coincidem'
                });
                reject();
                return;
            }
            
            // Obter dados do formulário
            const id = $('#usuarioId').val();
            const data = {
                nome: $('#nome').val(),
                email: $('#email').val(),
                ativo: $('#ativo').is(':checked') ? 1 : 0
            };
            
            // Adicionar senha apenas se foi preenchida
            if (senha) {
                data.senha = senha;
            }
            
            // Determinar se é criação ou atualização
            const url = id ? 
                `<?= base_url('api/usuarios') ?>/${id}` : 
                '<?= base_url('api/usuarios') ?>';
            const method = id ? 'PUT' : 'POST';
            
            $.ajax({
                url: url,
                type: method,
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function(response) {
                    if(response.status === 'success') {
                        $('#modalUsuario').modal('hide');
                        
                        // Mostrar mensagem de sucesso
                        const message = id ? 'Usuário atualizado com sucesso!' : 'Usuário criado com sucesso!';
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Recarregar usuários
                        loadUsuarios();
                        resolve();
                    } else {
                        console.error('Erro ao salvar usuário:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: response.message || 'Erro ao salvar usuário.'
                        });
                        reject();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error, xhr.responseText);
                    
                    let errorMsg = 'Erro ao salvar usuário. Tente novamente.';
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
    
    function excluirUsuario(id) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `<?= base_url('api/usuarios') ?>/${id}`,
                type: 'DELETE',
                success: function(response) {
                    if(response.status === 'success') {
                        $('#modalConfirmacao').modal('hide');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: 'Usuário excluído com sucesso!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Recarregar usuários
                        loadUsuarios();
                        resolve();
                    } else {
                        console.error('Erro ao excluir usuário:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: response.message || 'Erro ao excluir usuário.'
                        });
                        reject();
                    }
                },
                error: function(xhr, status, error) {
                    $('#modalConfirmacao').modal('hide');
                    console.error('Erro na requisição:', error, xhr.responseText);
                    
                    let errorMsg = 'Erro ao excluir usuário. Tente novamente.';
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