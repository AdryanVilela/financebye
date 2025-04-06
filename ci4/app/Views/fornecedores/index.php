<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-roxo mb-0"><i class="fas fa-building me-2"></i> Cadastro de Fornecedores</h1>
        <button type="button" class="btn btn-roxo shadow-sm" data-bs-toggle="modal" data-bs-target="#modalFornecedor">
            <i class="fas fa-plus-circle me-2"></i> Novo Fornecedor
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
                <form action="<?= site_url('fornecedores') ?>" method="get" class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label small text-muted fw-medium">Nome do Fornecedor</label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="nome" name="nome" placeholder="Buscar por nome" value="<?= $nome ?? '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="documento" class="form-label small text-muted fw-medium">CNPJ/CPF</label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="documento" name="documento" placeholder="Buscar por documento" value="<?= $documento ?? '' ?>">
                    </div>
                    <div class="col-12 d-flex justify-content-end mt-4">
                        <a href="<?= site_url('fornecedores') ?>" class="btn btn-light fw-medium shadow-sm me-2">
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
    
    <!-- Lista de Fornecedores -->
    <div class="card border-0 shadow-sm hover-card">
        <div class="card-header bg-white p-4 border-0">
            <h5 class="mb-0 fw-semibold text-roxo">
                <i class="fas fa-building me-2"></i> Fornecedores Cadastrados
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($fornecedores)) : ?>
                <div class="text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <p class="mb-0 text-muted">Nenhum fornecedor cadastrado.</p>
                    <button type="button" class="btn btn-roxo mt-3" data-bs-toggle="modal" data-bs-target="#modalFornecedor">
                        <i class="fas fa-plus-circle me-2"></i> Cadastrar Fornecedor
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
                            <?php foreach ($fornecedores as $fornecedor) : ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-medium"><?= esc($fornecedor['nome']) ?></div>
                                </td>
                                <td>
                                    <?= esc($fornecedor['documento'] ?? '-') ?>
                                </td>
                                <td>
                                    <?= esc($fornecedor['telefone'] ?? '-') ?>
                                </td>
                                <td>
                                    <?= esc($fornecedor['email'] ?? '-') ?>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <button type="button" class="btn-action edit" 
                                             data-id="<?= $fornecedor['id'] ?>"
                                             data-nome="<?= esc($fornecedor['nome']) ?>"
                                             data-documento="<?= esc($fornecedor['documento'] ?? '') ?>"
                                             data-telefone="<?= esc($fornecedor['telefone'] ?? '') ?>"
                                             data-email="<?= esc($fornecedor['email'] ?? '') ?>"
                                             data-endereco="<?= esc($fornecedor['endereco'] ?? '') ?>"
                                             data-observacoes="<?= esc($fornecedor['observacoes'] ?? '') ?>"
                                             data-bs-toggle="tooltip" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn-action delete" onclick="confirmarExclusao(<?= $fornecedor['id'] ?>)" data-bs-toggle="tooltip" title="Excluir">
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

<!-- Modal de cadastro/edição -->
<div class="modal fade" id="modalFornecedor" tabindex="-1" aria-labelledby="modalFornecedorLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold text-roxo" id="modalFornecedorLabel">
                    <i class="fas fa-plus-circle me-2"></i> Novo Fornecedor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <form id="formFornecedor" action="<?= site_url('fornecedor/salvar') ?>" method="post">
                    <input type="hidden" id="fornecedor_id" name="id">
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="nome_fornecedor" class="form-label small text-muted fw-medium">Nome do Fornecedor *</label>
                            <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="nome_fornecedor" name="nome" required>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="documento_fornecedor" class="form-label small text-muted fw-medium">CNPJ/CPF</label>
                            <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="documento_fornecedor" name="documento">
                        </div>
                        <div class="col-md-4">
                            <label for="telefone_fornecedor" class="form-label small text-muted fw-medium">Telefone</label>
                            <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="telefone_fornecedor" name="telefone">
                        </div>
                        <div class="col-md-4">
                            <label for="email_fornecedor" class="form-label small text-muted fw-medium">Email</label>
                            <input type="email" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="email_fornecedor" name="email">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="endereco_fornecedor" class="form-label small text-muted fw-medium">Endereço</label>
                            <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="endereco_fornecedor" name="endereco">
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label for="observacoes_fornecedor" class="form-label small text-muted fw-medium">Observações</label>
                            <textarea class="form-control form-control-lg rounded-3 shadow-sm border-0" id="observacoes_fornecedor" name="observacoes" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-0 p-3">
                <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="button" class="btn btn-roxo fw-medium shadow-sm" id="btnSalvarFornecedor">
                    <i class="fas fa-save me-2"></i> Salvar Fornecedor
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
            window.location.href = "<?= site_url('fornecedor/excluir/') ?>" + id;
        }
    });
}

// Editar fornecedor
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(function(tooltip) {
        return new bootstrap.Tooltip(tooltip);
    });
    
    // Ao clicar em editar, preencher o modal com os dados existentes
    const botoesEditar = document.querySelectorAll('.btn-action.edit');
    botoesEditar.forEach(botao => {
        botao.addEventListener('click', function() {
            const id = this.dataset.id;
            const nome = this.dataset.nome;
            const documento = this.dataset.documento;
            const telefone = this.dataset.telefone;
            const email = this.dataset.email;
            const endereco = this.dataset.endereco;
            const observacoes = this.dataset.observacoes;
            
            document.getElementById('fornecedor_id').value = id;
            document.getElementById('nome_fornecedor').value = nome;
            document.getElementById('documento_fornecedor').value = documento;
            document.getElementById('telefone_fornecedor').value = telefone;
            document.getElementById('email_fornecedor').value = email;
            document.getElementById('endereco_fornecedor').value = endereco;
            document.getElementById('observacoes_fornecedor').value = observacoes;
            
            document.getElementById('modalFornecedorLabel').innerHTML = '<i class="fas fa-edit me-2"></i> Editar Fornecedor';
            
            const modal = new bootstrap.Modal(document.getElementById('modalFornecedor'));
            modal.show();
        });
    });
    
    // Resetar o formulário quando o modal for aberto para um novo fornecedor
    const modalFornecedor = document.getElementById('modalFornecedor');
    modalFornecedor.addEventListener('show.bs.modal', function(event) {
        if (event.relatedTarget) {
            // Foi aberto pelo botão "Novo Fornecedor", então limpar o formulário
            document.getElementById('formFornecedor').reset();
            document.getElementById('fornecedor_id').value = '';
            document.getElementById('modalFornecedorLabel').innerHTML = '<i class="fas fa-plus-circle me-2"></i> Novo Fornecedor';
        }
    });
    
    // Submeter o formulário ao clicar no botão salvar
    document.getElementById('btnSalvarFornecedor').addEventListener('click', function() {
        document.getElementById('formFornecedor').submit();
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 