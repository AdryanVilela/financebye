<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <h1 class="h3 fw-bold text-roxo mb-0">Meu Perfil</h1>
    </div>
    
    <div class="row">
        <!-- Coluna de Informações Pessoais -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm hover-card mb-4">
                <div class="card-header bg-white p-4 border-0">
                    <h5 class="mb-0 fw-semibold text-roxo">
                        <i class="fas fa-user-circle me-2"></i> Informações Pessoais
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="perfilForm">
                        <!-- Nome -->
                        <div class="mb-4">
                            <label for="nome" class="form-label small text-muted fw-medium">Nome Completo</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text border-0 bg-light"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control form-control-lg rounded-end shadow-sm border-0" id="nome" placeholder="Digite seu nome completo" required>
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
                        
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-roxo fw-medium shadow-sm" id="salvarPerfil">
                                <i class="fas fa-save me-2"></i> Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Alterar Senha -->
            <div class="card border-0 shadow-sm hover-card">
                <div class="card-header bg-white p-4 border-0">
                    <h5 class="mb-0 fw-semibold text-roxo">
                        <i class="fas fa-lock me-2"></i> Alterar Senha
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="senhaForm">
                        <!-- Senha Atual -->
                        <div class="mb-4">
                            <label for="senhaAtual" class="form-label small text-muted fw-medium">Senha Atual</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text border-0 bg-light"><i class="fas fa-key"></i></span>
                                <input type="password" class="form-control form-control-lg rounded-end shadow-sm border-0" id="senhaAtual" placeholder="Digite sua senha atual" required>
                            </div>
                        </div>
                        
                        <!-- Nova Senha -->
                        <div class="mb-4">
                            <label for="novaSenha" class="form-label small text-muted fw-medium">Nova Senha</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text border-0 bg-light"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control form-control-lg rounded-end shadow-sm border-0" id="novaSenha" placeholder="Digite a nova senha" required>
                            </div>
                            <small class="form-text text-muted mt-2"><i class="fas fa-info-circle me-1"></i> Mínimo de 6 caracteres.</small>
                        </div>
                        
                        <!-- Confirmar Nova Senha -->
                        <div class="mb-4">
                            <label for="confirmarSenha" class="form-label small text-muted fw-medium">Confirmar Nova Senha</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text border-0 bg-light"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control form-control-lg rounded-end shadow-sm border-0" id="confirmarSenha" placeholder="Confirme a nova senha" required>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-roxo fw-medium shadow-sm" id="alterarSenha">
                                <i class="fas fa-key me-2"></i> Alterar Senha
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Coluna lateral com Resumo/Avatar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm hover-card mb-4">
                <div class="card-header bg-white p-4 border-0">
                    <h5 class="mb-0 fw-semibold text-roxo">
                        <i class="fas fa-id-badge me-2"></i> Perfil do Usuário
                    </h5>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="avatar-container mb-4">
                        <div class="avatar mx-auto mb-3" style="width: 120px; height: 120px; background-color: var(--roxo-light); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem;">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5 class="fw-bold mb-1" id="perfilNome">Carregando...</h5>
                        <p class="text-muted" id="perfilEmail">carregando@email.com</p>
                    </div>
                    
                    <div class="info-container text-start">
                        <div class="info-item d-flex align-items-center p-3 mb-3 bg-light rounded-3">
                            <div class="icon me-3 bg-roxo-light p-2 rounded-circle text-white">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <p class="small text-muted mb-0">Cadastrado em</p>
                                <p class="fw-medium mb-0" id="dataCadastro">--/--/----</p>
                            </div>
                        </div>
                        
                        <div class="info-item d-flex align-items-center p-3 mb-3 bg-light rounded-3">
                            <div class="icon me-3 bg-roxo-light p-2 rounded-circle text-white">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <p class="small text-muted mb-0">Último acesso</p>
                                <p class="fw-medium mb-0" id="ultimoAcesso">--/--/---- --:--</p>
                            </div>
                        </div>
                        
                        <div class="info-item d-flex align-items-center p-3 bg-light rounded-3">
                            <div class="icon me-3 bg-roxo-light p-2 rounded-circle text-white">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <p class="small text-muted mb-0">Status da conta</p>
                                <p class="fw-medium mb-0" id="statusConta">
                                    <span class="status-badge active">
                                        <i class="fas fa-check-circle"></i>Ativa
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
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

/* Ícones redondos no card lateral */
.icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script>
    $(document).ready(function() {
        // Carregar dados do perfil do usuário
        loadPerfil();
        
        // Handler para salvar alterações do perfil
        $('#salvarPerfil').click(function() {
            // Adicionar loading ao botão
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');
            $btn.prop('disabled', true);
            
            salvarPerfil().finally(() => {
                // Restaurar botão após operação
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            });
        });
        
        // Handler para alterar senha
        $('#alterarSenha').click(function() {
            // Adicionar loading ao botão
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processando...');
            $btn.prop('disabled', true);
            
            alterarSenha().finally(() => {
                // Restaurar botão após operação
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            });
        });
    });
    
    function loadPerfil() {
        showLoading();
        $.ajax({
            url: '<?= base_url('api/perfil') ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log('API Response:', response);  // Para debug
                hideLoading();
                
                if (response.success && response.data) {
                    const usuario = response.data;
                    
                    // Preencher dados do perfil com valores padrão caso estejam ausentes
                    $('#nome').val(usuario.nome || '');
                    $('#email').val(usuario.email || '');
                    
                    // Mostrar informações na sidebar
                    $('#perfilNome').text(usuario.nome || 'Usuário');
                    $('#perfilEmail').text(usuario.email || 'email@exemplo.com');
                    
                    // Formatar data de cadastro
                    if (usuario.created_at) {
                        const dataCadastro = formatDate(usuario.created_at);
                        $('#dataCadastro').text(dataCadastro);
                    } else {
                        $('#dataCadastro').text('N/A');
                    }
                    
                    // Formatar último acesso
                    if (usuario.ultimo_acesso) {
                        const ultimoAcesso = formatDateWithTime(usuario.ultimo_acesso);
                        $('#ultimoAcesso').text(ultimoAcesso);
                    } else {
                        $('#ultimoAcesso').text('N/A');
                    }
                    
                    // Status da conta
                    const isAtivo = usuario.ativo == 1 || usuario.status === 'ativo';
                    $('#statusConta').html(
                        isAtivo 
                        ? '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Ativo</span>' 
                        : '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Inativo</span>'
                    );
                } else {
                    // Mensagem de erro específica baseada na resposta
                    let errorMsg = 'Erro ao carregar dados do perfil';
                    if (response.message) {
                        errorMsg = response.message;
                    } else if (!response.success) {
                        errorMsg = 'A API retornou um erro desconhecido';
                    } else if (!response.data) {
                        errorMsg = 'A API não retornou dados de usuário';
                    }
                    
                    showError(errorMsg);
                    console.error('Erro na API de perfil:', response);
                }
            },
            error: function(xhr, status, error) {
                hideLoading();
                showError('Não foi possível carregar os dados do seu perfil. Erro: ' + (error || 'Desconhecido'));
                console.error('Erro ao carregar perfil:', xhr, status, error);
            }
        });
    }
    
    function salvarPerfil() {
        return new Promise((resolve, reject) => {
            // Validar formulário
            const form = document.getElementById('perfilForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                reject();
                return;
            }
            
            // Obter dados do formulário
            const data = {
                nome: $('#nome').val(),
                email: $('#email').val()
            };
            
            $.ajax({
                url: '<?= base_url('api/perfil') ?>',
                type: 'PUT',
                data: data,
                success: function(response) {
                    if(response.success) {
                        // Mostrar mensagem de sucesso
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: 'Perfil atualizado com sucesso!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Recarregar dados do perfil
                        loadPerfil();
                        
                        // Atualizar nome de usuário na interface
                        if (typeof atualizarNomeUsuario === 'function') {
                            atualizarNomeUsuario(data.nome);
                        }
                        
                        resolve();
                    } else {
                        console.error('Erro ao salvar perfil:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: response.message || 'Erro ao salvar perfil.'
                        });
                        reject();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error, xhr.responseText);
                    
                    let errorMsg = 'Erro ao salvar perfil. Tente novamente.';
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
    
    function alterarSenha() {
        return new Promise((resolve, reject) => {
            // Validar formulário
            const form = document.getElementById('senhaForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                reject();
                return;
            }
            
            // Validar se as senhas coincidem
            const senhaAtual = $('#senhaAtual').val();
            const novaSenha = $('#novaSenha').val();
            const confirmarSenha = $('#confirmarSenha').val();
            
            if (novaSenha !== confirmarSenha) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'A nova senha e a confirmação não coincidem.'
                });
                reject();
                return;
            }
            
            // Obter dados do formulário
            const data = {
                senha_atual: senhaAtual,
                nova_senha: novaSenha
            };
            
            $.ajax({
                url: '<?= base_url('api/perfil/senha') ?>',
                type: 'POST',
                data: data,
                success: function(response) {
                    if(response.success) {
                        // Limpar campos de senha
                        $('#senhaAtual').val('');
                        $('#novaSenha').val('');
                        $('#confirmarSenha').val('');
                        
                        // Mostrar mensagem de sucesso
                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: 'Senha alterada com sucesso!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        resolve();
                    } else {
                        console.error('Erro ao alterar senha:', response);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: response.message || 'Erro ao alterar senha.'
                        });
                        reject();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro na requisição:', error, xhr.responseText);
                    
                    let errorMsg = 'Erro ao alterar senha. Tente novamente.';
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
    
    // Funções auxiliares
    function showLoading() {
        // Adicionar um overlay de loading
        if ($('#loadingOverlay').length === 0) {
            $('body').append(`
                <div id="loadingOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                    background-color: rgba(0,0,0,0.5); z-index: 9999; display: flex; justify-content: center; 
                    align-items: center;">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
            `);
        } else {
            $('#loadingOverlay').show();
        }
    }

    function hideLoading() {
        $('#loadingOverlay').hide();
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: message
        });
    }

    // Função para formatar data (corrigida)
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        
        // Usar split e construir a data manualmente para evitar problemas de fuso horário
        const parts = dateString.split(/[-T ]/);
        if (parts.length >= 3) {
            // Formato ano-mes-dia vindo do banco (2025-04-05)
            const dia = parts[2].split(':')[0]; // Para o caso de ter hora junto
            const mes = parts[1];
            const ano = parts[0];
            return `${dia}/${mes}/${ano}`;
        }
        
        return dateString;
    }

    // Função para formatar data e hora
    function formatDateWithTime(dateTimeString) {
        if (!dateTimeString) return 'N/A';
        
        // Converter para formato brasileiro
        try {
            // Se for formato ISO
            if (dateTimeString.includes('T')) {
                const [datePart, timePart] = dateTimeString.split('T');
                return `${formatDate(datePart)} ${timePart.substring(0, 5)}`;
            }
            
            // Se for formato com espaço
            if (dateTimeString.includes(' ')) {
                const [datePart, timePart] = dateTimeString.split(' ');
                return `${formatDate(datePart)} ${timePart.substring(0, 5)}`;
            }
            
            return formatDate(dateTimeString);
        } catch (e) {
            console.error('Erro ao formatar data e hora:', e);
            return dateTimeString;
        }
    }
</script>
<?= $this->endSection() ?> 