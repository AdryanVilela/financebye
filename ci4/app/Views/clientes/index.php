<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-roxo mb-0"><i class="fas fa-user-friends me-2"></i> Cadastro de Clientes</h1>
        <button type="button" class="btn btn-roxo shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCliente">
            <i class="fas fa-plus-circle me-2"></i> Novo Cliente
        </button>
    </div>
    
    <?php if (session()->getFlashdata('mensagem')) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Sucesso!',
                text: '<?= session()->getFlashdata('mensagem') ?>',
                icon: 'success',
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false
            });
        });
    </script>
    <?php endif; ?>
    
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
                <form action="<?= site_url('clientes') ?>" method="get" class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label small text-muted fw-medium">Nome do Cliente</label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="nome" name="nome" placeholder="Buscar por nome" value="<?= $nome ?? '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="documento" class="form-label small text-muted fw-medium">CPF/CNPJ</label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="documento" name="documento" placeholder="Buscar por documento" value="<?= $documento ?? '' ?>">
                    </div>
                    <div class="col-12 d-flex justify-content-end mt-4">
                        <a href="<?= site_url('clientes') ?>" class="btn btn-light fw-medium shadow-sm me-2">
                            <i class="fas fa-eraser me-2"></i> Limpar
                        </a>
                        <button type="submit" class="btn btn-roxo fw-medium shadow-sm">
                            <i class="fas fa-search me-2"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Lista de Clientes -->
    <div class="card border-0 shadow-sm hover-card">
        <div class="card-header bg-white p-4 border-0">
            <h5 class="mb-0 fw-semibold text-roxo">
                <i class="fas fa-user-friends me-2"></i> Clientes Cadastrados
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($clientes)) : ?>
                <div class="text-center py-5">
                    <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                    <p class="mb-0 text-muted">Nenhum cliente cadastrado.</p>
                    <button type="button" class="btn btn-roxo mt-3" data-bs-toggle="modal" data-bs-target="#modalCliente">
                        <i class="fas fa-plus-circle me-2"></i> Cadastrar Cliente
                    </button>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 ps-4">Nome</th>
                                <th class="border-0 py-3">Documento</th>
                                <th class="border-0 py-3">Telefone</th>
                                <th class="border-0 py-3">Email</th>
                                <th class="border-0 py-3 text-center pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clientes as $cliente) : ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-medium"><?= esc($cliente['nome']) ?></div>
                                </td>
                                <td>
                                    <?= esc($cliente['documento'] ?? '-') ?>
                                </td>
                                <td>
                                    <?= esc($cliente['telefone'] ?? '-') ?>
                                </td>
                                <td>
                                    <?= esc($cliente['email'] ?? '-') ?>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <button type="button" class="btn-action edit" 
                                             data-id="<?= $cliente['id'] ?>"
                                             data-nome="<?= esc($cliente['nome']) ?>"
                                             data-documento="<?= esc($cliente['documento'] ?? '') ?>"
                                             data-telefone="<?= esc($cliente['telefone'] ?? '') ?>"
                                             data-email="<?= esc($cliente['email'] ?? '') ?>"
                                             data-endereco="<?= esc($cliente['endereco'] ?? '') ?>"
                                             data-observacoes="<?= esc($cliente['observacoes'] ?? '') ?>"
                                             data-bs-toggle="tooltip" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn-action delete" onclick="confirmarExclusao(<?= $cliente['id'] ?>)" data-bs-toggle="tooltip" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de Cliente -->
<div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold text-roxo" id="modalClienteLabel">
                    <i class="fas fa-plus-circle me-2"></i> Novo Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <form id="clienteForm" action="<?= site_url('clientes/salvar') ?>" method="post">
                    <input type="hidden" id="clienteId" name="id">
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="formNome" class="form-label small text-muted fw-medium">Nome do Cliente *</label>
                            <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="formNome" name="nome" required>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="formDocumento" class="form-label small text-muted fw-medium">CPF/CNPJ</label>
                            <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="formDocumento" name="documento">
                        </div>
                        <div class="col-md-4">
                            <label for="formTelefone" class="form-label small text-muted fw-medium">Telefone</label>
                            <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="formTelefone" name="telefone">
                        </div>
                        <div class="col-md-4">
                            <label for="formEmail" class="form-label small text-muted fw-medium">Email</label>
                            <input type="email" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="formEmail" name="email">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="formEndereco" class="form-label small text-muted fw-medium">Endereço</label>
                            <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="formEndereco" name="endereco">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="formObservacoes" class="form-label small text-muted fw-medium">Observações</label>
                            <textarea class="form-control form-control-lg rounded-3 shadow-sm border-0" id="formObservacoes" name="observacoes" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-0 p-3">
                <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="button" class="btn btn-roxo fw-medium shadow-sm" id="btnSalvarCliente">
                    <i class="fas fa-save me-2"></i> Salvar Cliente
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<style>
/* Estilos para botões de ação na tabela */
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
    border: 1px solid rgba(0,0,0,.1);
    color: #6c757d;
    margin: 0 2px;
}

.btn-action:hover {
    background: var(--roxo-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,.15);
}

.btn-action.view:hover {
    background: #17a2b8;
}

.btn-action.edit:hover {
    background: #ffc107;
    color: #212529;
}

.btn-action.update:hover {
    background: #28a745;
}

.btn-action.delete:hover {
    background: #dc3545;
}
</style>

<script>
function confirmarExclusao(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta ação não poderá ser revertida!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#693976',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= site_url('clientes/excluir/') ?>" + id;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(function (tooltip) {
        return new bootstrap.Tooltip(tooltip);
    });
    
    // Inicializar modal
    const modalCliente = document.getElementById('modalCliente');
    if (modalCliente) {
        modalCliente.addEventListener('show.bs.modal', function(event) {
            // Verificar se o modal foi acionado por um botão através do relatedTarget
            // Se não foi (ex: foi aberto pelo JavaScript ao editar), não resetar o formulário
            if (event.relatedTarget) {
                // Foi aberto pelo botão "Novo Cliente", então limpar o formulário
                document.getElementById('clienteForm').reset();
                document.getElementById('clienteId').value = '';
                document.getElementById('modalClienteLabel').innerHTML = '<i class="fas fa-plus-circle me-2"></i> Novo Cliente';
            }
        });
    }
    
    // Salvar cliente
    document.getElementById('btnSalvarCliente').addEventListener('click', function() {
        // Validar formulário antes de enviar
        const form = document.getElementById('clienteForm');
        if (form.checkValidity()) {
            // Mostrar loading
            const btnSalvar = this;
            const textoOriginal = btnSalvar.innerHTML;
            btnSalvar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';
            btnSalvar.disabled = true;
            
            // Enviar formulário
            form.submit();
        } else {
            // Mostrar validação nativa do navegador
            form.reportValidity();
            
            // Destacar campos inválidos com SweetAlert
            const camposInvalidos = form.querySelectorAll(':invalid');
            if (camposInvalidos.length > 0) {
                const primeiroInvalido = camposInvalidos[0];
                const nomeCampo = primeiroInvalido.previousElementSibling?.textContent || 'Um campo';
                
                Swal.fire({
                    title: 'Atenção!',
                    text: `${nomeCampo} precisa ser preenchido corretamente.`,
                    icon: 'warning',
                    confirmButtonColor: '#693976'
                });
            }
        }
    });
    
    // Editar cliente
    const botoesEditar = document.querySelectorAll('.btn-action.edit');
    botoesEditar.forEach(function(botao) {
        botao.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nome = this.getAttribute('data-nome');
            const documento = this.getAttribute('data-documento');
            const telefone = this.getAttribute('data-telefone');
            const email = this.getAttribute('data-email');
            const endereco = this.getAttribute('data-endereco');
            const observacoes = this.getAttribute('data-observacoes');
            
            // Preencher formulário
            document.getElementById('clienteId').value = id;
            document.getElementById('formNome').value = nome;
            document.getElementById('formDocumento').value = documento;
            document.getElementById('formTelefone').value = telefone;
            document.getElementById('formEmail').value = email;
            document.getElementById('formEndereco').value = endereco;
            document.getElementById('formObservacoes').value = observacoes;
            
            // Alterar título do modal
            document.getElementById('modalClienteLabel').innerHTML = '<i class="fas fa-edit me-2"></i> Editar Cliente';
            
            // Abrir modal
            const modal = new bootstrap.Modal(document.getElementById('modalCliente'));
            modal.show();
        });
    });
    
    // Máscaras para campos
    const documentoInput = document.getElementById('formDocumento');
    if (documentoInput) {
        documentoInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                // Máscara de CPF: 000.000.000-00
                if (value.length > 9) {
                    value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
                } else if (value.length > 6) {
                    value = value.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
                } else if (value.length > 3) {
                    value = value.replace(/(\d{3})(\d{1,3})/, '$1.$2');
                }
            } else {
                // Máscara de CNPJ: 00.000.000/0000-00
                if (value.length > 12) {
                    value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{1,2})/, '$1.$2.$3/$4-$5');
                } else if (value.length > 8) {
                    value = value.replace(/(\d{2})(\d{3})(\d{3})(\d{1,4})/, '$1.$2.$3/$4');
                } else if (value.length > 5) {
                    value = value.replace(/(\d{2})(\d{3})(\d{1,3})/, '$1.$2.$3');
                } else if (value.length > 2) {
                    value = value.replace(/(\d{2})(\d{1,3})/, '$1.$2');
                }
            }
            e.target.value = value;
        });
    }
    
    const telefoneInput = document.getElementById('formTelefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                // Celular: (00) 00000-0000
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length > 6) {
                // Fixo: (00) 0000-0000
                value = value.replace(/(\d{2})(\d{4})(\d{1,4})/, '($1) $2-$3');
            } else if (value.length > 2) {
                // DDD apenas: (00)
                value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            }
            e.target.value = value;
        });
    }
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 