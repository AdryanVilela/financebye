<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-roxo mb-0">
            <?= $tipo == 'receber' ? 'Contas a Receber' : 'Contas a Pagar' ?>
        </h1>
        <div>
            <div class="btn-group me-2">
                <a href="<?= site_url('contas?tipo=receber') ?>" class="btn <?= $tipo == 'receber' ? 'btn-roxo' : 'btn-light' ?> shadow-sm">
                    <i class="fas fa-hand-holding-usd me-2"></i> A Receber
                </a>
                <a href="<?= site_url('contas?tipo=pagar') ?>" class="btn <?= $tipo == 'pagar' ? 'btn-roxo' : 'btn-light' ?> shadow-sm">
                    <i class="fas fa-money-bill-wave me-2"></i> A Pagar
                </a>
            </div>
            <a href="<?= site_url('contas/novo?tipo=' . $tipo) ?>" class="btn btn-roxo shadow-sm">
                <i class="fas fa-plus me-2"></i> Nova Conta
            </a>
        </div>
    </div>
    
    <?php if (session()->getFlashdata('mensagem')) : ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <?= session()->getFlashdata('mensagem') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
    <?php endif; ?>
    
    <!-- Resumo -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
            <div class="card border-0 shadow-sm hover-card bg-light">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-md bg-white shadow-sm rounded-3 p-2">
                                <i class="fas fa-circle text-success"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 text-muted small">Total <?= $tipo == 'receber' ? 'Recebido' : 'Pago' ?></p>
                            <h4 class="mb-0 fw-bold">R$ <?= number_format($totalConcluido, 2, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
            <div class="card border-0 shadow-sm hover-card bg-light">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-md bg-white shadow-sm rounded-3 p-2">
                                <i class="fas fa-circle text-warning"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 text-muted small">Total Pendente</p>
                            <h4 class="mb-0 fw-bold">R$ <?= number_format($totalPendente, 2, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
            <div class="card border-0 shadow-sm hover-card bg-light">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-md bg-white shadow-sm rounded-3 p-2">
                                <i class="fas fa-circle text-danger"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 text-muted small">Total Atrasado</p>
                            <h4 class="mb-0 fw-bold">R$ <?= number_format($totalAtrasado, 2, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3 mb-lg-0">
            <div class="card border-0 shadow-sm hover-card bg-roxo text-white">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-md bg-white shadow-sm text-roxo rounded-3 p-2">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 small">Total Geral</p>
                            <h4 class="mb-0 fw-bold">R$ <?= number_format($totalGeral, 2, ',', '.') ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                <form action="<?= site_url('contas') ?>" method="get" class="row g-3">
                    <input type="hidden" name="tipo" value="<?= $tipo ?>">
                    
                    <div class="col-md-4">
                        <label for="status" class="form-label small text-muted fw-medium">Status</label>
                        <select class="form-select form-select-lg rounded-3 shadow-sm border-0" id="status" name="status">
                            <option value="">Todos os status</option>
                            <option value="pendente" <?= $this->request->getGet('status') == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                            <option value="concluido" <?= $this->request->getGet('status') == 'concluido' ? 'selected' : '' ?>><?= $tipo == 'receber' ? 'Recebido' : 'Pago' ?></option>
                            <option value="atrasado" <?= $this->request->getGet('status') == 'atrasado' ? 'selected' : '' ?>>Atrasado</option>
                            <option value="cancelado" <?= $this->request->getGet('status') == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="data_vencimento" class="form-label small text-muted fw-medium">Data de Vencimento</label>
                        <input type="date" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="data_vencimento" name="data_vencimento" value="<?= $this->request->getGet('data_vencimento') ?>">
                    </div>
                    
                    <div class="col-md-4">
                        <label for="entidade_id" class="form-label small text-muted fw-medium"><?= $tipo == 'receber' ? 'Cliente' : 'Fornecedor' ?></label>
                        <select class="form-select form-select-lg rounded-3 shadow-sm border-0" id="entidade_id" name="entidade_id">
                            <option value="">Todos</option>
                            <?php foreach ($entidades as $entidade) : ?>
                                <option value="<?= $entidade['id'] ?>" <?= $this->request->getGet('entidade_id') == $entidade['id'] ? 'selected' : '' ?>>
                                    <?= esc($entidade['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-12 d-flex justify-content-end mt-4">
                        <a href="<?= site_url('contas?tipo=' . $tipo) ?>" class="btn btn-light fw-medium shadow-sm me-2">
                            <i class="fas fa-eraser me-2"></i> Limpar
                        </a>
                        <button type="submit" class="btn btn-roxo fw-medium shadow-sm">
                            <i class="fas fa-search me-2"></i> Aplicar Filtros
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Lista de Contas -->
    <div class="card border-0 shadow-sm hover-card">
        <div class="card-header bg-white p-4 border-0">
            <h5 class="mb-0 fw-semibold text-roxo">
                <i class="<?= $tipo == 'receber' ? 'fas fa-hand-holding-usd' : 'fas fa-money-bill-wave' ?> me-2"></i> 
                Contas <?= $tipo == 'receber' ? 'a Receber' : 'a Pagar' ?>
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($contas)) : ?>
                <div class="text-center py-5">
                    <i class="<?= $tipo == 'receber' ? 'fas fa-hand-holding-usd' : 'fas fa-money-bill-wave' ?> fa-3x text-muted mb-3"></i>
                    <p class="mb-0 text-muted">Nenhuma conta encontrada.</p>
                    <a href="<?= site_url('contas/novo?tipo=' . $tipo) ?>" class="btn btn-roxo mt-3">
                        <i class="fas fa-plus me-2"></i> Cadastrar Nova Conta
                    </a>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 ps-4">Descrição</th>
                                <th class="border-0 py-3"><?= $tipo == 'receber' ? 'Cliente' : 'Fornecedor' ?></th>
                                <th class="border-0 py-3">Vencimento</th>
                                <th class="border-0 py-3">Categoria</th>
                                <th class="border-0 py-3 text-end">Valor</th>
                                <th class="border-0 py-3 text-center">Status</th>
                                <th class="border-0 py-3 text-center pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contas as $conta) : ?>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-medium"><?= esc($conta['descricao']) ?></div>
                                    <small class="text-muted">
                                        Emissão: <?= date('d/m/Y', strtotime($conta['data_emissao'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <?= esc($conta['entidade_nome'] ?? '-') ?>
                                </td>
                                <td>
                                    <?= date('d/m/Y', strtotime($conta['data_vencimento'])) ?>
                                    <?php if ($conta['status'] == 'concluido' && !empty($conta['data_pagamento'])) : ?>
                                        <div class="small text-success">
                                            <?= $tipo == 'receber' ? 'Recebido' : 'Pago' ?> em: <?= date('d/m/Y', strtotime($conta['data_pagamento'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= esc($conta['categoria_nome'] ?? '-') ?>
                                </td>
                                <td class="text-end fw-bold">
                                    R$ <?= number_format($conta['valor'], 2, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <?php
                                        $statusClass = '';
                                        $statusText = '';
                                        
                                        switch ($conta['status']) {
                                            case 'pendente':
                                                $statusClass = 'bg-warning';
                                                $statusText = 'Pendente';
                                                break;
                                            case 'concluido':
                                                $statusClass = 'bg-success';
                                                $statusText = ($tipo == 'receber') ? 'Recebido' : 'Pago';
                                                break;
                                            case 'atrasado':
                                                $statusClass = 'bg-danger';
                                                $statusText = 'Atrasado';
                                                break;
                                            case 'cancelado':
                                                $statusClass = 'bg-secondary';
                                                $statusText = 'Cancelado';
                                                break;
                                        }
                                    ?>
                                    <span class="badge <?= $statusClass ?> rounded-pill"><?= $statusText ?></span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <?php if ($conta['status'] == 'pendente' || $conta['status'] == 'atrasado') : ?>
                                            <button type="button" class="btn btn-sm btn-light" onclick="confirmarBaixa(<?= $conta['id'] ?>)" data-bs-toggle="tooltip" title="<?= $tipo == 'receber' ? 'Receber' : 'Pagar' ?>">
                                                <i class="fas fa-check-circle text-success"></i>
                                            </button>
                                        <?php endif; ?>
                                        <a href="<?= site_url('contas/editar/'.$conta['id']) ?>" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Editar">
                                            <i class="fas fa-edit text-roxo"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-light" onclick="confirmarExclusao(<?= $conta['id'] ?>)" data-bs-toggle="tooltip" title="Excluir">
                                            <i class="fas fa-trash-alt text-danger"></i>
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

<?= $this->section('scripts') ?>
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
            window.location.href = "<?= site_url('contas/excluir/') ?>" + id;
        }
    });
}

function confirmarBaixa(id) {
    Swal.fire({
        title: 'Confirmar <?= $tipo == 'receber' ? 'recebimento' : 'pagamento' ?>?',
        text: "Esta ação registrará o <?= $tipo == 'receber' ? 'recebimento' : 'pagamento' ?> e criará uma transação correspondente.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, <?= $tipo == 'receber' ? 'receber' : 'pagar' ?>!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "<?= site_url('contas/baixar/') ?>" + id;
        }
    });
}

// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(function (tooltip) {
        return new bootstrap.Tooltip(tooltip);
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 