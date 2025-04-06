<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-bullseye mr-2"></i>
                        Detalhes da Meta
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('metas') ?>">Metas</a></li>
                        <li class="breadcrumb-item active">Detalhes</li>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <?php if (!empty($meta['icone'])) : ?>
                                    <i class="fas <?= $meta['icone'] ?>" style="color: <?= $meta['cor'] ?? '#007bff' ?>"></i>
                                <?php endif; ?>
                                <?= $meta['titulo'] ?>
                            </h3>
                            <div class="card-tools">
                                <a href="<?= site_url('metas/editar/' . $meta['id']) ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <a href="<?= site_url('metas') ?>" class="btn btn-secondary btn-sm ml-2">
                                    <i class="fas fa-arrow-left"></i> Voltar
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h5 class="card-title">Informações da Meta</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <th style="width: 140px;">Título:</th>
                                                            <td><?= $meta['titulo'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Categoria:</th>
                                                            <td><?= $meta['categoria_nome'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Valor Atual:</th>
                                                            <td>R$ <?= number_format($meta['valor_atual'], 2, ',', '.') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Valor Alvo:</th>
                                                            <td>R$ <?= number_format($meta['valor_alvo'], 2, ',', '.') ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Faltam:</th>
                                                            <td>R$ <?= number_format(max(0, $meta['valor_alvo'] - $meta['valor_atual']), 2, ',', '.') ?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="table table-borderless">
                                                        <tr>
                                                            <th style="width: 140px;">Data Início:</th>
                                                            <td><?= date('d/m/Y', strtotime($meta['data_inicio'])) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Data Alvo:</th>
                                                            <td>
                                                                <?= date('d/m/Y', strtotime($meta['data_alvo'])) ?>
                                                                <?php
                                                                $hoje = new DateTime();
                                                                $dataAlvo = new DateTime($meta['data_alvo']);
                                                                $diasRestantes = $hoje->diff($dataAlvo)->days;
                                                                $passouPrazo = $hoje > $dataAlvo;
                                                                ?>
                                                                <?php if ($meta['status'] !== 'concluida') : ?>
                                                                    <?php if ($passouPrazo) : ?>
                                                                        <span class="badge badge-danger">Atrasada</span>
                                                                    <?php elseif ($diasRestantes <= 7) : ?>
                                                                        <span class="badge badge-warning">
                                                                            <?= $diasRestantes ?> dias restantes
                                                                        </span>
                                                                    <?php else : ?>
                                                                        <span class="badge badge-info">
                                                                            <?= $diasRestantes ?> dias restantes
                                                                        </span>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Status:</th>
                                                            <td>
                                                                <?php
                                                                $statusLabel = '';
                                                                $statusClass = '';
                                                                
                                                                switch ($meta['status']) {
                                                                    case 'concluida':
                                                                        $statusLabel = 'Concluída';
                                                                        $statusClass = 'badge-success';
                                                                        break;
                                                                    case 'cancelada':
                                                                        $statusLabel = 'Cancelada';
                                                                        $statusClass = 'badge-danger';
                                                                        break;
                                                                    default:
                                                                        $statusLabel = 'Em Andamento';
                                                                        $statusClass = 'badge-primary';
                                                                        break;
                                                                }
                                                                ?>
                                                                <span class="badge <?= $statusClass ?>"><?= $statusLabel ?></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>Criada em:</th>
                                                            <td><?= date('d/m/Y H:i', strtotime($meta['created_at'])) ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Atualizada em:</th>
                                                            <td><?= date('d/m/Y H:i', strtotime($meta['updated_at'])) ?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <?php if (!empty($meta['descricao'])) : ?>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="callout callout-info">
                                                        <h5><i class="fas fa-info-circle"></i> Descrição</h5>
                                                        <p><?= nl2br($meta['descricao']) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h5 class="card-title">Progresso</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center">
                                                <input type="text" class="knob" value="<?= $meta['progresso'] ?>" data-width="200" data-height="200" 
                                                       data-fgColor="<?= $meta['cor'] ?? '#007bff' ?>" data-readonly="true" readonly>
                                            </div>
                                            <div class="mt-4">
                                                <div class="progress progress-lg">
                                                    <?php 
                                                        $corClasse = $meta['progresso'] < 25 ? 'bg-danger' : ($meta['progresso'] < 50 ? 'bg-warning' : ($meta['progresso'] < 75 ? 'bg-info' : 'bg-success'));
                                                    ?>
                                                    <div class="progress-bar progress-bar-striped <?= $corClasse ?>" role="progressbar" 
                                                         aria-valuenow="<?= $meta['progresso'] ?>" aria-valuemin="0" aria-valuemax="100" 
                                                         style="width: <?= $meta['progresso'] ?>%">
                                                        <?= number_format($meta['progresso'], 1) ?>%
                                                    </div>
                                                </div>
                                                <div class="text-center mt-3">
                                                    <button type="button" class="btn btn-success btn-lg btn-atualizar" 
                                                            data-id="<?= $meta['id'] ?>" 
                                                            data-title="<?= $meta['titulo'] ?>"
                                                            data-atual="<?= $meta['valor_atual'] ?>"
                                                            data-alvo="<?= $meta['valor_alvo'] ?>">
                                                        <i class="fas fa-sync-alt"></i> Atualizar Progresso
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($meta['status'] === 'em_andamento'): ?>
                                    <div class="card bg-light mt-3">
                                        <div class="card-header">
                                            <h5 class="card-title">Planejamento</h5>
                                        </div>
                                        <div class="card-body">
                                            <?php
                                            $hoje = new DateTime();
                                            $dataAlvo = new DateTime($meta['data_alvo']);
                                            $dataInicio = new DateTime($meta['data_inicio']);
                                            
                                            $totalDias = $dataInicio->diff($dataAlvo)->days;
                                            $diasPassados = $dataInicio->diff($hoje)->days;
                                            
                                            $diasRestantes = max(0, $dataAlvo->diff($hoje)->days);
                                            
                                            $tempoDecorrido = $totalDias > 0 ? min(100, ($diasPassados / $totalDias) * 100) : 100;
                                            $valorRestante = max(0, $meta['valor_alvo'] - $meta['valor_atual']);
                                            $valorDiario = $diasRestantes > 0 ? $valorRestante / $diasRestantes : 0;
                                            ?>
                                            <table class="table">
                                                <tr>
                                                    <th>Tempo decorrido:</th>
                                                    <td><?= number_format($tempoDecorrido, 1) ?>%</td>
                                                </tr>
                                                <tr>
                                                    <th>Dias restantes:</th>
                                                    <td><?= $diasRestantes ?> dias</td>
                                                </tr>
                                                <tr>
                                                    <th>Valor que falta:</th>
                                                    <td>R$ <?= number_format($valorRestante, 2, ',', '.') ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Valor diário necessário:</th>
                                                    <td>R$ <?= number_format($valorDiario, 2, ',', '.') ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="<?= site_url('metas') ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-1"></i> Voltar para a lista
                                    </a>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="<?= site_url('metas/editar/' . $meta['id']) ?>" class="btn btn-warning">
                                        <i class="fas fa-edit mr-1"></i> Editar Meta
                                    </a>
                                    <a href="<?= site_url('metas/excluir/' . $meta['id']) ?>" class="btn btn-danger ml-2 btn-excluir" data-id="<?= $meta['id'] ?>">
                                        <i class="fas fa-trash mr-1"></i> Excluir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal de Atualização de Meta -->
<div class="modal fade" id="atualizarMetaModal" tabindex="-1" role="dialog" aria-labelledby="atualizarMetaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="atualizarMetaModalLabel">Atualizar Progresso da Meta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="meta_id" value="<?= $meta['id'] ?>">
                <div class="form-group">
                    <label for="meta_titulo">Meta</label>
                    <input type="text" class="form-control" id="meta_titulo" value="<?= $meta['titulo'] ?>" readonly>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="valor_atual">Valor Atual (R$)</label>
                            <input type="number" class="form-control" id="valor_atual" step="0.01" min="0" value="<?= $meta['valor_atual'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="valor_alvo">Valor Alvo (R$)</label>
                            <input type="text" class="form-control" id="valor_alvo" value="<?= $meta['valor_alvo'] ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Progresso</label>
                    <div class="progress" style="height: 25px;">
                        <div id="barra_progresso" class="progress-bar bg-success progress-bar-striped" role="progressbar" 
                            style="width: <?= $meta['progresso'] ?>%;" aria-valuenow="<?= $meta['progresso'] ?>" aria-valuemin="0" aria-valuemax="100">
                            <?= number_format($meta['progresso'], 1) ?>%
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btn_salvar_atualizacao">Salvar</button>
            </div>
        </div>
    </div>
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
                <p>Tem certeza que deseja excluir a meta <strong><?= $meta['titulo'] ?></strong>?</p>
                <p class="text-warning">Esta ação não poderá ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <a href="<?= site_url('metas/excluir/' . $meta['id']) ?>" class="btn btn-danger">Confirmar Exclusão</a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicializar knob para gráfico circular
    $('.knob').knob({
        'format': function(value) {
            return value + '%';
        }
    });
    
    // Abrir modal de atualização de progresso
    $('.btn-atualizar').on('click', function() {
        $('#atualizarMetaModal').modal('show');
    });
    
    // Atualizar barra de progresso conforme o valor é alterado
    $('#valor_atual').on('input', function() {
        var valorAtual = parseFloat($(this).val()) || 0;
        var valorAlvo = parseFloat($('#valor_alvo').val()) || 1;
        var progresso = Math.min(100, (valorAtual / valorAlvo) * 100);
        
        atualizarBarraProgresso(progresso);
    });
    
    // Função para atualizar a barra de progresso
    function atualizarBarraProgresso(progresso) {
        $('#barra_progresso').css('width', progresso + '%');
        $('#barra_progresso').attr('aria-valuenow', progresso);
        $('#barra_progresso').text(progresso.toFixed(1) + '%');
        
        // Alterar cor da barra conforme progresso
        $('#barra_progresso').removeClass('bg-danger bg-warning bg-info bg-success');
        
        if (progresso < 25) {
            $('#barra_progresso').addClass('bg-danger');
        } else if (progresso < 50) {
            $('#barra_progresso').addClass('bg-warning');
        } else if (progresso < 75) {
            $('#barra_progresso').addClass('bg-info');
        } else {
            $('#barra_progresso').addClass('bg-success');
        }
    }
    
    // Salvar atualização do progresso
    $('#btn_salvar_atualizacao').on('click', function() {
        var id = $('#meta_id').val();
        var valorAtual = $('#valor_atual').val();
        
        if (!valorAtual) {
            alert('Informe o valor atual da meta.');
            return;
        }
        
        $.ajax({
            url: '<?= site_url('metas/atualizar/') ?>' + id,
            type: 'POST',
            data: {
                valor_atual: valorAtual
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#atualizarMetaModal').modal('hide');
                    
                    // Mostrar mensagem de sucesso
                    var alert = '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                                response.message +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span></button></div>';
                    
                    $('.content-header').after(alert);
                    
                    // Recarregar a página após 1 segundo para atualizar os dados
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    alert('Erro: ' + response.message);
                }
            },
            error: function() {
                alert('Erro ao processar a requisição. Tente novamente.');
            }
        });
    });
    
    // Confirmação de exclusão
    $('.btn-excluir').on('click', function(e) {
        e.preventDefault();
        $('#confirmDeleteModal').modal('show');
    });
});
</script>
<?= $this->endSection() ?> 