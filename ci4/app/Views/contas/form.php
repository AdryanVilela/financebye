<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-roxo mb-0">
            <?= isset($conta) ? ($tipo == 'receber' ? 'Editar Conta a Receber' : 'Editar Conta a Pagar') : ($tipo == 'receber' ? 'Nova Conta a Receber' : 'Nova Conta a Pagar') ?>
        </h1>
        <div>
            <div class="btn-group me-2">
                <a href="<?= site_url('contas/novo?tipo=receber') ?>" class="btn <?= $tipo == 'receber' ? 'btn-roxo' : 'btn-light' ?> shadow-sm">
                    <i class="fas fa-hand-holding-usd me-2"></i> A Receber
                </a>
                <a href="<?= site_url('contas/novo?tipo=pagar') ?>" class="btn <?= $tipo == 'pagar' ? 'btn-roxo' : 'btn-light' ?> shadow-sm">
                    <i class="fas fa-money-bill-wave me-2"></i> A Pagar
                </a>
            </div>
            <a href="<?= site_url('contas?tipo=' . $tipo) ?>" class="btn btn-light border shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Voltar
            </a>
        </div>
    </div>
    
    <!-- Formulário -->
    <div class="card border-0 shadow-sm hover-card">
        <div class="card-body p-4">
            <form action="<?= site_url('contas/salvar') ?>" method="post">
                <?php if (isset($conta)) : ?>
                    <input type="hidden" name="id" value="<?= $conta['id'] ?>">
                <?php endif; ?>
                <input type="hidden" name="tipo" value="<?= $tipo ?>">
                
                <div class="row mb-4">
                    <div class="col-md-8">
                        <label for="descricao" class="form-label small text-muted fw-medium">Descrição *</label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="descricao" name="descricao" value="<?= isset($conta) ? $conta['descricao'] : '' ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="valor" class="form-label small text-muted fw-medium">Valor *</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light fw-medium">R$</span>
                            <input type="number" class="form-control form-control-lg rounded-end shadow-sm border-0" id="valor" name="valor" step="0.01" min="0.01" value="<?= isset($conta) ? $conta['valor'] : '' ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="data_emissao" class="form-label small text-muted fw-medium">Data de Emissão *</label>
                        <input type="date" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="data_emissao" name="data_emissao" value="<?= isset($conta) ? $conta['data_emissao'] : date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="data_vencimento" class="form-label small text-muted fw-medium">Data de Vencimento *</label>
                        <input type="date" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="data_vencimento" name="data_vencimento" value="<?= isset($conta) ? $conta['data_vencimento'] : '' ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="status" class="form-label small text-muted fw-medium">Status *</label>
                        <select class="form-select form-select-lg rounded-3 shadow-sm border-0" id="status" name="status" required>
                            <option value="pendente" <?= isset($conta) && $conta['status'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                            <option value="concluido" <?= isset($conta) && $conta['status'] == 'concluido' ? 'selected' : '' ?>><?= $tipo == 'receber' ? 'Recebido' : 'Pago' ?></option>
                            <option value="atrasado" <?= isset($conta) && $conta['status'] == 'atrasado' ? 'selected' : '' ?>>Atrasado</option>
                            <option value="cancelado" <?= isset($conta) && $conta['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-4 data-pagamento-row" style="<?= isset($conta) && $conta['status'] == 'concluido' ? '' : 'display: none;' ?>">
                    <div class="col-md-4">
                        <label for="data_pagamento" class="form-label small text-muted fw-medium">Data de <?= $tipo == 'receber' ? 'Recebimento' : 'Pagamento' ?></label>
                        <input type="date" class="form-control form-control-lg rounded-3 shadow-sm border-0" id="data_pagamento" name="data_pagamento" value="<?= isset($conta) && !empty($conta['data_pagamento']) ? $conta['data_pagamento'] : date('Y-m-d') ?>">
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="categoria_id" class="form-label small text-muted fw-medium">Categoria</label>
                        <select class="form-select form-select-lg rounded-3 shadow-sm border-0" id="categoria_id" name="categoria_id">
                            <option value="">Selecione uma categoria</option>
                            <?php foreach ($categorias as $categoria) : ?>
                                <option value="<?= $categoria['id'] ?>" <?= isset($conta) && $conta['categoria_id'] == $categoria['id'] ? 'selected' : '' ?>>
                                    <?= esc($categoria['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="entidade_id" class="form-label small text-muted fw-medium"><?= $tipo == 'receber' ? 'Cliente' : 'Fornecedor' ?></label>
                        <select class="form-select form-select-lg rounded-3 shadow-sm border-0" id="entidade_id" name="entidade_id">
                            <option value="">Selecione <?= $tipo == 'receber' ? 'um cliente' : 'um fornecedor' ?></option>
                            <?php foreach ($entidades as $entidade) : ?>
                                <?php 
                                $selected = false;
                                if (isset($conta)) {
                                    if ($tipo == 'receber' && $conta['cliente_id'] == $entidade['id']) {
                                        $selected = true;
                                    } else if ($tipo == 'pagar' && $conta['fornecedor_id'] == $entidade['id']) {
                                        $selected = true;
                                    }
                                }
                                ?>
                                <option value="<?= $entidade['id'] ?>" <?= $selected ? 'selected' : '' ?>>
                                    <?= esc($entidade['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label for="observacoes" class="form-label small text-muted fw-medium">Observações</label>
                        <textarea class="form-control form-control-lg rounded-3 shadow-sm border-0" id="observacoes" name="observacoes" rows="3"><?= isset($conta) ? $conta['observacoes'] : '' ?></textarea>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-light fw-medium shadow-sm me-2">
                        <i class="fas fa-eraser me-2"></i> Limpar
                    </button>
                    <button type="submit" class="btn btn-roxo fw-medium shadow-sm">
                        <i class="fas fa-save me-2"></i> Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar campo de data de pagamento quando status for alterado
    const statusSelect = document.getElementById('status');
    const dataPagamentoRow = document.querySelector('.data-pagamento-row');
    
    statusSelect.addEventListener('change', function() {
        if (this.value === 'concluido') {
            dataPagamentoRow.style.display = '';
        } else {
            dataPagamentoRow.style.display = 'none';
        }
    });
    
    // Máscara para valor monetário
    const valorInput = document.getElementById('valor');
    valorInput.addEventListener('blur', function(e) {
        let value = e.target.value;
        
        // Formatar para duas casas decimais
        if (value !== '') {
            e.target.value = parseFloat(value).toFixed(2);
        }
    });
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 