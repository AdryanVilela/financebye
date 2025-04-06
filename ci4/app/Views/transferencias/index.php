<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-exchange-alt mr-2"></i>Transferências</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Home</a></li>
                        <li class="breadcrumb-item active">Transferências</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php if (session()->has('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if (session()->has('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Estatísticas -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>R$ <?= number_format($estatisticas['total_transferido'], 2, ',', '.') ?></h3>
                            <p>Total Transferido</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>R$ <?= number_format($estatisticas['total_taxas'], 2, ',', '.') ?></h3>
                            <p>Total em Taxas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= $estatisticas['quantidade'] ?></h3>
                            <p>Transferências no Mês</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3><?= count($carteiras) ?></h3>
                            <p>Carteiras Ativas</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros e Nova Transferência -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Filtros</h3>
                            <div class="card-tools">
                                <a href="<?= site_url('transferencias/nova') ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Nova Transferência
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="filtroForm" class="row">
                                <div class="col-md-3 form-group">
                                    <label>Data Início</label>
                                    <input type="date" class="form-control" id="data_inicio" name="data_inicio" value="<?= $data_inicio ?>">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Data Fim</label>
                                    <input type="date" class="form-control" id="data_fim" name="data_fim" value="<?= $data_fim ?>">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Carteira</label>
                                    <select class="form-control" id="carteira_id" name="carteira_id">
                                        <option value="">Todas as Carteiras</option>
                                        <?php foreach ($carteiras as $carteira) : ?>
                                            <option value="<?= $carteira['id'] ?>"><?= $carteira['nome'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group d-flex align-items-end">
                                    <button type="submit" class="btn btn-info btn-block">
                                        <i class="fas fa-filter"></i> Filtrar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Transferências -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Transferências Realizadas</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="transferenciasTable">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>De</th>
                                            <th>Para</th>
                                            <th>Valor</th>
                                            <th>Taxa</th>
                                            <th>Descrição</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transferenciasBody">
                                        <?php if (empty($transferencias)) : ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Nenhuma transferência encontrada no período.</td>
                                            </tr>
                                        <?php else : ?>
                                            <?php foreach ($transferencias as $transferencia) : ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($transferencia['data'])) ?></td>
                                                    <td><?= $transferencia['carteira_origem_nome'] ?></td>
                                                    <td><?= $transferencia['carteira_destino_nome'] ?></td>
                                                    <td class="text-right">R$ <?= number_format($transferencia['valor'], 2, ',', '.') ?></td>
                                                    <td class="text-right">R$ <?= number_format($transferencia['taxa'], 2, ',', '.') ?></td>
                                                    <td><?= $transferencia['descricao'] ?></td>
                                                    <td class="text-center">
                                                        <a href="<?= site_url('transferencias/detalhes/' . $transferencia['id']) ?>" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="<?= site_url('transferencias/excluir/' . $transferencia['id']) ?>" class="btn btn-danger btn-sm btn-excluir" data-id="<?= $transferencia['id'] ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
    // Inicializar DataTable
    var dataTable = $('#transferenciasTable').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
        },
        "order": [[0, "desc"]]
    });
    
    // Filtrar transferências
    $('#filtroForm').on('submit', function(e) {
        e.preventDefault();
        
        // Exibir loader
        $('#transferenciasBody').html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Carregando...</td></tr>');
        
        // Obter dados do formulário
        var formData = $(this).serialize();
        
        // Fazer requisição AJAX
        $.ajax({
            url: '<?= site_url('transferencias/filtrar') ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Limpar tabela
                    dataTable.clear();
                    
                    // Verificar se há transferências
                    if (response.transferencias.length === 0) {
                        dataTable.row.add([
                            '',
                            '',
                            '',
                            'Nenhuma transferência encontrada no período.',
                            '',
                            '',
                            ''
                        ]).draw();
                    } else {
                        // Adicionar transferências à tabela
                        $.each(response.transferencias, function(i, transferencia) {
                            var dataFormatada = new Date(transferencia.data).toLocaleDateString('pt-BR');
                            var valorFormatado = 'R$ ' + parseFloat(transferencia.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            var taxaFormatada = 'R$ ' + parseFloat(transferencia.taxa).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            
                            var acoes = `
                                <a href="<?= site_url('transferencias/detalhes/') ?>${transferencia.id}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm btn-excluir" data-id="${transferencia.id}">
                                    <i class="fas fa-trash"></i>
                                </a>
                            `;
                            
                            dataTable.row.add([
                                dataFormatada,
                                transferencia.carteira_origem_nome,
                                transferencia.carteira_destino_nome,
                                valorFormatado,
                                taxaFormatada,
                                transferencia.descricao,
                                acoes
                            ]).draw();
                        });
                    }
                    
                    // Atualizar estatísticas
                    atualizarEstatisticas();
                } else {
                    alert('Erro ao filtrar transferências');
                }
            },
            error: function() {
                alert('Erro ao comunicar com o servidor');
            }
        });
    });
    
    // Atualizar estatísticas
    function atualizarEstatisticas() {
        var dataInicio = $('#data_inicio').val();
        var dataFim = $('#data_fim').val();
        
        // Obter mês e ano da data início
        var dataObj = new Date(dataInicio);
        var mes = dataObj.getMonth() + 1;
        var ano = dataObj.getFullYear();
        
        $.ajax({
            url: '<?= site_url('transferencias/estatisticas') ?>',
            type: 'POST',
            data: {
                mes: mes,
                ano: ano
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Atualizar cards de estatísticas
                    $('.small-box.bg-info .inner h3').text('R$ ' + parseFloat(response.estatisticas.total_transferido).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('.small-box.bg-warning .inner h3').text('R$ ' + parseFloat(response.estatisticas.total_taxas).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('.small-box.bg-success .inner h3').text(response.estatisticas.quantidade);
                }
            }
        });
    }
    
    // Confirmar exclusão
    $(document).on('click', '.btn-excluir', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#confirmDeleteBtn').attr('href', '<?= site_url('transferencias/excluir/') ?>' + id);
        $('#confirmDeleteModal').modal('show');
    });
});
</script> 