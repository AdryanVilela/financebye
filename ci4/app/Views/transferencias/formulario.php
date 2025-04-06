<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><i class="fas fa-exchange-alt mr-2"></i>Nova Transferência</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('transferencias') ?>">Transferências</a></li>
                        <li class="breadcrumb-item active">Nova</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Dados da Transferência</h3>
                        </div>

                        <form method="post" action="<?= site_url('transferencias/nova') ?>">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="carteira_origem_id">Carteira de Origem <span class="text-danger">*</span></label>
                                            <select class="form-control" id="carteira_origem_id" name="carteira_origem_id" required>
                                                <option value="">Selecione a carteira de origem</option>
                                                <?php foreach ($carteiras as $carteira) : ?>
                                                    <option value="<?= $carteira['id'] ?>" data-saldo="<?= $carteira['saldo'] ?>">
                                                        <?= $carteira['nome'] ?> (Saldo: R$ <?= number_format($carteira['saldo'], 2, ',', '.') ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="text-muted">Carteira da qual o dinheiro será retirado.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="carteira_destino_id">Carteira de Destino <span class="text-danger">*</span></label>
                                            <select class="form-control" id="carteira_destino_id" name="carteira_destino_id" required>
                                                <option value="">Selecione a carteira de destino</option>
                                                <?php foreach ($carteiras as $carteira) : ?>
                                                    <option value="<?= $carteira['id'] ?>"><?= $carteira['nome'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="text-muted">Carteira para a qual o dinheiro será transferido.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="valor">Valor da Transferência <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">R$</span>
                                                </div>
                                                <input type="number" class="form-control" id="valor" name="valor" step="0.01" min="0.01" required>
                                            </div>
                                            <small class="text-muted">Valor a ser transferido entre as carteiras.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="taxa">Taxa de Transferência</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">R$</span>
                                                </div>
                                                <input type="number" class="form-control" id="taxa" name="taxa" step="0.01" min="0" value="0">
                                            </div>
                                            <small class="text-muted">Opcional. Será debitada da carteira de origem.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="data">Data da Transferência <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="data" name="data" value="<?= date('Y-m-d') ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="descricao">Descrição</label>
                                            <textarea class="form-control" id="descricao" name="descricao" rows="3" placeholder="Descrição opcional para esta transferência"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            <strong>Resumo:</strong> 
                                            <span id="resumo-transferencia">
                                                Selecione as carteiras e informe o valor para visualizar o resumo da transferência.
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="btnSalvar">
                                    <i class="fas fa-check mr-1"></i> Realizar Transferência
                                </button>
                                <a href="<?= site_url('transferencias') ?>" class="btn btn-secondary ml-2">
                                    <i class="fas fa-times mr-1"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Inicializar campos
    $('#valor, #taxa, #carteira_origem_id, #carteira_destino_id').on('change', function() {
        atualizarResumo();
    });

    // Função para atualizar o resumo da transferência
    function atualizarResumo() {
        var carteiraOrigemId = $('#carteira_origem_id').val();
        var carteiraDestinoId = $('#carteira_destino_id').val();
        var valor = parseFloat($('#valor').val()) || 0;
        var taxa = parseFloat($('#taxa').val()) || 0;
        
        if (carteiraOrigemId && carteiraDestinoId && valor > 0) {
            var carteiraOrigemNome = $('#carteira_origem_id option:selected').text().split('(')[0].trim();
            var carteiraDestinoNome = $('#carteira_destino_id option:selected').text().split('(')[0].trim();
            var saldoOrigem = parseFloat($('#carteira_origem_id option:selected').data('saldo')) || 0;
            var valorTotal = valor + taxa;
            
            var valorFormatado = valor.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
            var taxaFormatada = taxa.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
            var valorTotalFormatado = valorTotal.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
            var saldoAposFormatado = (saldoOrigem - valorTotal).toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
            
            var html = `Transferir <strong>${valorFormatado}</strong> da carteira <strong>${carteiraOrigemNome}</strong> para <strong>${carteiraDestinoNome}</strong>`;
            
            if (taxa > 0) {
                html += ` com taxa de <strong>${taxaFormatada}</strong> (Total: <strong>${valorTotalFormatado}</strong>)`;
            }
            
            html += `. Saldo após a transferência na carteira de origem: <strong>${saldoAposFormatado}</strong>.`;
            
            // Verificar se há saldo suficiente
            if (saldoOrigem < valorTotal) {
                html += `<div class="text-danger mt-2"><i class="fas fa-exclamation-triangle"></i> <strong>Atenção:</strong> Saldo insuficiente na carteira de origem!</div>`;
                $('#btnSalvar').prop('disabled', true);
            } else {
                $('#btnSalvar').prop('disabled', false);
            }
            
            $('#resumo-transferencia').html(html);
        } else {
            $('#resumo-transferencia').text('Selecione as carteiras e informe o valor para visualizar o resumo da transferência.');
            $('#btnSalvar').prop('disabled', false);
        }
    }
    
    // Impedir que a mesma carteira seja selecionada como origem e destino
    $('#carteira_origem_id, #carteira_destino_id').on('change', function() {
        var carteiraOrigemId = $('#carteira_origem_id').val();
        var carteiraDestinoId = $('#carteira_destino_id').val();
        
        if (carteiraOrigemId && carteiraDestinoId && carteiraOrigemId === carteiraDestinoId) {
            alert('A carteira de origem e destino não podem ser a mesma!');
            $(this).val('');
            atualizarResumo();
        }
    });
});
</script> 