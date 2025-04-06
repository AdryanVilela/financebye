<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-exchange-alt mr-2"></i>Detalhes da Transferência</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('transferencias') ?>">Transferências</a></li>
                        <li class="breadcrumb-item active">Detalhes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Informações da Transferência</h3>
                            <div class="card-tools">
                                <a href="<?= site_url('transferencias/excluir/' . $transferencia['id']) ?>" class="btn btn-danger btn-sm btn-excluir" data-id="<?= $transferencia['id'] ?>">
                                    <i class="fas fa-trash"></i> Excluir
                                </a>
                                <a href="<?= site_url('transferencias') ?>" class="btn btn-secondary btn-sm ml-1">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h5 class="card-title">Dados Principais</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th style="width: 200px;">ID</th>
                                                    <td>#<?= $transferencia['id'] ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Data</th>
                                                    <td><?= date('d/m/Y', strtotime($transferencia['data'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Valor Transferido</th>
                                                    <td>R$ <?= number_format($transferencia['valor'], 2, ',', '.') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Taxa</th>
                                                    <td>R$ <?= number_format($transferencia['taxa'], 2, ',', '.') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Valor Total</th>
                                                    <td><strong>R$ <?= number_format($transferencia['valor'] + $transferencia['taxa'], 2, ',', '.') ?></strong></td>
                                                </tr>
                                                <tr>
                                                    <th>Descrição</th>
                                                    <td><?= !empty($transferencia['descricao']) ? $transferencia['descricao'] : '<i>Sem descrição</i>' ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Data do Cadastro</th>
                                                    <td><?= date('d/m/Y H:i', strtotime($transferencia['created_at'])) ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Última Atualização</th>
                                                    <td><?= date('d/m/Y H:i', strtotime($transferencia['updated_at'])) ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h5 class="card-title">Carteiras</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info-box bg-danger">
                                                        <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Carteira de Origem</span>
                                                            <span class="info-box-number"><?= $transferencia['carteira_origem_nome'] ?></span>
                                                            <div class="progress">
                                                                <div class="progress-bar" style="width: 100%"></div>
                                                            </div>
                                                            <span class="progress-description">
                                                                Saída: R$ <?= number_format($transferencia['valor'] + $transferencia['taxa'], 2, ',', '.') ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Carteira de Destino</span>
                                                            <span class="info-box-number"><?= $transferencia['carteira_destino_nome'] ?></span>
                                                            <div class="progress">
                                                                <div class="progress-bar" style="width: 100%"></div>
                                                            </div>
                                                            <span class="progress-description">
                                                                Entrada: R$ <?= number_format($transferencia['valor'], 2, ',', '.') ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mt-4">
                                                <div class="alert alert-info">
                                                    <h5><i class="icon fas fa-info"></i> Resumo da Operação</h5>
                                                    <p>
                                                        Esta transferência movimentou <strong>R$ <?= number_format($transferencia['valor'], 2, ',', '.') ?></strong> 
                                                        da carteira <strong><?= $transferencia['carteira_origem_nome'] ?></strong> 
                                                        para a carteira <strong><?= $transferencia['carteira_destino_nome'] ?></strong>
                                                        <?php if ($transferencia['taxa'] > 0) : ?>
                                                            com uma taxa de <strong>R$ <?= number_format($transferencia['taxa'], 2, ',', '.') ?></strong>.
                                                        <?php else : ?>
                                                            sem taxas adicionais.
                                                        <?php endif; ?>
                                                    </p>
                                                    <?php if ($transferencia['taxa'] > 0) : ?>
                                                        <p>
                                                            <strong>Valor debitado da origem:</strong> R$ <?= number_format($transferencia['valor'] + $transferencia['taxa'], 2, ',', '.') ?><br>
                                                            <strong>Valor creditado no destino:</strong> R$ <?= number_format($transferencia['valor'], 2, ',', '.') ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir esta transferência?</p>
                <p class="text-warning"><small>Esta ação irá reverter as alterações nos saldos das carteiras.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Confirmar Exclusão</a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Confirmar exclusão
    $('.btn-excluir').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#confirmDeleteBtn').attr('href', '<?= site_url('transferencias/excluir/') ?>' + id);
        $('#confirmDeleteModal').modal('show');
    });
});
</script> 