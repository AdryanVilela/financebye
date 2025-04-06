<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-roxo mb-0">Minhas Metas Financeiras</h1>
        <button type="button" class="btn btn-roxo shadow-sm" data-bs-toggle="modal" data-bs-target="#modalMeta">
            <i class="fas fa-plus me-2"></i> Nova Meta
        </button>
    </div>

    <!-- Resumo das Metas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px; font-weight: 600;">Total de Metas</p>
                            <h3 class="mb-0 fw-bold"><?= count($metas) ?></h3>
                            <span class="badge bg-primary-subtle text-primary mt-2"><i class="fas fa-list me-1"></i> Metas ativas</span>
                        </div>
                        <div class="bg-gradient-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="fas fa-list text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px; font-weight: 600;">Em Andamento</p>
                            <h3 class="mb-0 fw-bold"><?= count($metas_em_andamento) ?></h3>
                            <span class="badge bg-warning-subtle text-warning mt-2"><i class="fas fa-hourglass-half me-1"></i> Metas ativas</span>
                        </div>
                        <div class="bg-gradient-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="fas fa-hourglass-half text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px; font-weight: 600;">Concluídas</p>
                            <h3 class="mb-0 fw-bold"><?= count($metas_concluidas) ?></h3>
                            <span class="badge bg-success-subtle text-success mt-2"><i class="fas fa-check-circle me-1"></i> Metas finalizadas</span>
                        </div>
                        <div class="bg-gradient-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="fas fa-check-circle text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php
                            $totalAtual = array_sum(array_column($metas, 'valor_atual'));
                            $totalAlvo = array_sum(array_column($metas, 'valor_alvo'));
                            $progresso = ($totalAlvo > 0) ? min(100, ($totalAtual / $totalAlvo) * 100) : 0;
                            ?>
                            <p class="text-uppercase text-muted mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px; font-weight: 600;">Progresso Geral</p>
                            <h3 class="mb-0 fw-bold"><?= number_format($progresso, 1) ?>%</h3>
                            <span class="badge bg-primary-subtle text-primary mt-2"><i class="fas fa-chart-line me-1"></i> Economia realizada</span>
                        </div>
                        <div class="bg-gradient-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="fas fa-chart-line text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Metas em Andamento -->
    <div class="card border-0 shadow-sm hover-card mb-4">
        <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-roxo">
                <i class="fas fa-hourglass-half me-2"></i> Metas em Andamento
            </h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($metas_em_andamento)) : ?>
                <div class="alert alert-info m-4">
                    <i class="fas fa-info-circle me-2"></i> Você não possui metas em andamento. 
                    <button type="button" class="alert-link border-0 bg-transparent p-0" data-bs-toggle="modal" data-bs-target="#modalMeta">
                        Criar uma nova meta
                    </button>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 py-3 ps-4">Título</th>
                                <th class="border-0 py-3">Categoria</th>
                                <th class="border-0 py-3">Valor Atual</th>
                                <th class="border-0 py-3">Valor Alvo</th>
                                <th class="border-0 py-3">Progresso</th>
                                <th class="border-0 py-3">Data Alvo</th>
                                <th class="border-0 py-3 text-center pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($metas_em_andamento as $meta) : ?>
                                <tr>
                                    <td class="ps-4">
                                        <?php if (!empty($meta['icone'])) : ?>
                                            <i class="fas <?= $meta['icone'] ?>" style="color: <?= $meta['cor'] ?? '#007bff' ?>"></i>
                                        <?php endif; ?>
                                        <?= $meta['titulo'] ?>
                                    </td>
                                    <td><?= $meta['categoria_nome'] ?? 'Sem categoria' ?></td>
                                    <td>R$ <?= number_format($meta['valor_atual'], 2, ',', '.') ?></td>
                                    <td>R$ <?= number_format($meta['valor_alvo'], 2, ',', '.') ?></td>
                                    <td>
                                        <div class="progress" style="height: 8px; width: 120px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                aria-valuenow="<?= $meta['progresso'] ?>" aria-valuemin="0" aria-valuemax="100" 
                                                style="width: <?= $meta['progresso'] ?>%">
                                            </div>
                                        </div>
                                        <small class="mt-1"><?= number_format($meta['progresso'], 1) ?>%</small>
                                    </td>
                                    <td>
                                        <?= date('d/m/Y', strtotime($meta['data_alvo'])) ?>
                                        <?php
                                        $hoje = new DateTime();
                                        $dataAlvo = new DateTime($meta['data_alvo']);
                                        $diasRestantes = $hoje->diff($dataAlvo)->days;
                                        $passouPrazo = $hoje > $dataAlvo;
                                        ?>
                                        <?php if ($passouPrazo) : ?>
                                            <span class="badge bg-danger">Atrasada</span>
                                        <?php elseif ($diasRestantes <= 7) : ?>
                                            <span class="badge bg-warning text-dark">
                                                <?= $diasRestantes ?> dias restantes
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group">
                                            <button type="button" class="btn-action view" data-id="<?= $meta['id'] ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn-action edit" data-id="<?= $meta['id'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn-action update" 
                                                    data-id="<?= $meta['id'] ?>" 
                                                    data-title="<?= $meta['titulo'] ?>"
                                                    data-atual="<?= $meta['valor_atual'] ?>"
                                                    data-alvo="<?= $meta['valor_alvo'] ?>">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                            <a href="<?= site_url('metas/excluir/' . $meta['id']) ?>" 
                                               class="btn-action delete"
                                               onclick="return confirm('Tem certeza que deseja excluir esta meta?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
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

    <!-- Metas Concluídas -->
    <div class="card border-0 shadow-sm hover-card">
        <div class="card-header bg-white p-4 border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold text-roxo">
                <i class="fas fa-check-circle me-2"></i> Metas Concluídas
            </h5>
            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#metasConcluidas" aria-expanded="false">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="collapse" id="metasConcluidas">
            <div class="card-body p-0">
                <?php if (empty($metas_concluidas)) : ?>
                    <div class="alert alert-info m-4">
                        <i class="fas fa-info-circle me-2"></i> Você ainda não possui metas concluídas.
                    </div>
                <?php else : ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 py-3 ps-4">Título</th>
                                    <th class="border-0 py-3">Categoria</th>
                                    <th class="border-0 py-3">Valor Alcançado</th>
                                    <th class="border-0 py-3">Meta</th>
                                    <th class="border-0 py-3">Concluída em</th>
                                    <th class="border-0 py-3 text-center pe-4">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($metas_concluidas as $meta) : ?>
                                    <tr>
                                        <td class="ps-4">
                                            <?php if (!empty($meta['icone'])) : ?>
                                                <i class="fas <?= $meta['icone'] ?>" style="color: <?= $meta['cor'] ?? '#28a745' ?>"></i>
                                            <?php endif; ?>
                                            <?= $meta['titulo'] ?>
                                        </td>
                                        <td><?= $meta['categoria_nome'] ?? 'Sem categoria' ?></td>
                                        <td>R$ <?= number_format($meta['valor_atual'], 2, ',', '.') ?></td>
                                        <td>R$ <?= number_format($meta['valor_alvo'], 2, ',', '.') ?></td>
                                        <td><?= date('d/m/Y', strtotime($meta['updated_at'])) ?></td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group">
                                                <button type="button" class="btn-action view" data-id="<?= $meta['id'] ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="<?= site_url('metas/excluir/' . $meta['id']) ?>" 
                                                   class="btn-action delete"
                                                   onclick="return confirm('Tem certeza que deseja excluir esta meta?');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
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
</div>

<!-- Modal Atualizar Progresso -->
<div class="modal fade" id="atualizarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold text-roxo">
                    <i class="fas fa-sync-alt me-2"></i> Atualizar Progresso
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <form id="atualizarForm">
                    <input type="hidden" id="metaId">
                    
                    <div class="mb-4">
                        <h6 class="fw-bold" id="metaTituloProgresso"></h6>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-success" id="metaProgressoBar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Valor Atual: <span id="metaValorAtualText">R$ 0,00</span></small>
                            <small class="text-muted">Meta: <span id="metaValorAlvoText">R$ 0,00</span></small>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="valorAtual" class="form-label small text-muted fw-medium">Novo Valor Atual</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light fw-medium">R$</span>
                            <input type="number" class="form-control form-control-lg rounded-end shadow-sm border-0" id="valorAtual" step="0.01" min="0" placeholder="0,00" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-0 p-3">
                <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="button" class="btn btn-roxo fw-medium shadow-sm" id="btnSalvarProgresso">
                    <i class="fas fa-save me-2"></i> Salvar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Nova/Editar Meta -->
<div class="modal fade" id="modalMeta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold text-roxo" id="modalMetaLabel">
                    <i class="fas fa-plus-circle me-2"></i> Nova Meta Financeira
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <form id="metaForm">
                    <input type="hidden" id="metaIdEdit">
                    
                    <div class="row g-4">
                        <!-- Título -->
                        <div class="col-md-6">
                            <label for="metaTitulo" class="form-label small text-muted fw-medium">Título da Meta *</label>
                            <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" 
                                   id="metaTitulo" placeholder="Ex: Viagem para o Nordeste" required>
                        </div>

                        <!-- Categoria -->
                        <div class="col-md-6">
                            <label for="metaCategoria" class="form-label small text-muted fw-medium">Categoria (opcional)</label>
                            <select class="select2-modal form-select form-select-lg rounded-3 shadow-sm border-0" id="metaCategoria">
                                <option value="">Selecione uma categoria</option>
                                <?php foreach ($categorias as $categoria) : ?>
                                    <option value="<?= $categoria['id'] ?>"><?= $categoria['nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Valor Alvo -->
                        <div class="col-md-6">
                            <label for="metaValorAlvo" class="form-label small text-muted fw-medium">Valor Alvo *</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text border-0 bg-light fw-medium">R$</span>
                                <input type="number" class="form-control form-control-lg rounded-end shadow-sm border-0" 
                                       id="metaValorAlvo" step="0.01" min="0.01" placeholder="0,00" required>
                            </div>
                        </div>

                        <!-- Valor Atual -->
                        <div class="col-md-6">
                            <label for="metaValorAtual" class="form-label small text-muted fw-medium">Valor Atual</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text border-0 bg-light fw-medium">R$</span>
                                <input type="number" class="form-control form-control-lg rounded-end shadow-sm border-0" 
                                       id="metaValorAtual" step="0.01" min="0" placeholder="0,00">
                            </div>
                        </div>

                        <!-- Data Alvo -->
                        <div class="col-md-6">
                            <label for="metaDataAlvo" class="form-label small text-muted fw-medium">Data Alvo *</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text border-0 bg-light"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" class="form-control form-control-lg rounded-end shadow-sm border-0 date-input" 
                                       id="metaDataAlvo" placeholder="Selecionar data" required>
                            </div>
                        </div>

                        <!-- Status (apenas para edição, será adicionado via JS) -->
                        <div class="col-md-6" id="metaStatusContainer" style="display:none;">
                            <label for="metaStatus" class="form-label small text-muted fw-medium">Status</label>
                            <select class="form-select form-select-lg rounded-3 shadow-sm border-0" id="metaStatus">
                                <option value="em_andamento">Em andamento</option>
                                <option value="concluida">Concluída</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>

                        <!-- Ícone -->
                        <div class="col-md-6">
                            <label for="metaIcone" class="form-label small text-muted fw-medium">Ícone (opcional)</label>
                            <input type="hidden" id="metaIcone" name="metaIcone">
                            <div class="icon-selector p-3 rounded-3 shadow-sm border-0 bg-light overflow-auto" style="max-height: 200px;">
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    <!-- Ícones comuns para finanças/metas -->
                                    <button type="button" class="btn btn-icon" data-icon="fa-home"><i class="fas fa-home"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-car"><i class="fas fa-car"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-plane"><i class="fas fa-plane"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-graduation-cap"><i class="fas fa-graduation-cap"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-laptop"><i class="fas fa-laptop"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-mobile-alt"><i class="fas fa-mobile-alt"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-shopping-cart"><i class="fas fa-shopping-cart"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-gift"><i class="fas fa-gift"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-heart"><i class="fas fa-heart"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-briefcase"><i class="fas fa-briefcase"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-university"><i class="fas fa-university"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-piggy-bank"><i class="fas fa-piggy-bank"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-money-bill-wave"><i class="fas fa-money-bill-wave"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-coins"><i class="fas fa-coins"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-credit-card"><i class="fas fa-credit-card"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-chart-line"><i class="fas fa-chart-line"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-chart-pie"><i class="fas fa-chart-pie"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-chart-bar"><i class="fas fa-chart-bar"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-dollar-sign"><i class="fas fa-dollar-sign"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-euro-sign"><i class="fas fa-euro-sign"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-pound-sign"><i class="fas fa-pound-sign"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-bitcoin"><i class="fab fa-bitcoin"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-hand-holding-usd"><i class="fas fa-hand-holding-usd"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-wallet"><i class="fas fa-wallet"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-trophy"><i class="fas fa-trophy"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-star"><i class="fas fa-star"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-certificate"><i class="fas fa-certificate"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-award"><i class="fas fa-award"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-gem"><i class="fas fa-gem"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-ring"><i class="fas fa-ring"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-building"><i class="fas fa-building"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-house-user"><i class="fas fa-house-user"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-hotel"><i class="fas fa-hotel"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-umbrella-beach"><i class="fas fa-umbrella-beach"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-mountain"><i class="fas fa-mountain"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-globe-americas"><i class="fas fa-globe-americas"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-map-marker-alt"><i class="fas fa-map-marker-alt"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-baby"><i class="fas fa-baby"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-baby-carriage"><i class="fas fa-baby-carriage"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-book"><i class="fas fa-book"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-medkit"><i class="fas fa-medkit"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-heartbeat"><i class="fas fa-heartbeat"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-stethoscope"><i class="fas fa-stethoscope"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-utensils"><i class="fas fa-utensils"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-hamburger"><i class="fas fa-hamburger"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-pizza-slice"><i class="fas fa-pizza-slice"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-coffee"><i class="fas fa-coffee"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-cocktail"><i class="fas fa-cocktail"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-dog"><i class="fas fa-dog"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-cat"><i class="fas fa-cat"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-paw"><i class="fas fa-paw"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-pen"><i class="fas fa-pen"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-paint-brush"><i class="fas fa-paint-brush"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-camera"><i class="fas fa-camera"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-music"><i class="fas fa-music"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-guitar"><i class="fas fa-guitar"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-gamepad"><i class="fas fa-gamepad"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-bicycle"><i class="fas fa-bicycle"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-running"><i class="fas fa-running"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-dumbbell"><i class="fas fa-dumbbell"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-tshirt"><i class="fas fa-tshirt"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-shoe-prints"><i class="fas fa-shoe-prints"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-ring"><i class="fas fa-ring"></i></button>
                                    <button type="button" class="btn btn-icon" data-icon="fa-glasses"><i class="fas fa-glasses"></i></button>
                                </div>
                            </div>
                            <div class="mt-2 text-center">
                                <span class="badge bg-light text-muted icon-preview">
                                    <i class="fas fa-bullseye"></i> Ícone selecionado
                                </span>
                            </div>
                        </div>

                        <!-- Cor -->
                        <div class="col-md-6">
                            <label for="metaCor" class="form-label small text-muted fw-medium">Cor (opcional)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text border-0 bg-light"><i class="fas fa-palette"></i></span>
                                <input type="color" class="form-control form-control-lg rounded-end shadow-sm border-0 form-control-color" 
                                       id="metaCor" value="#007bff">
                            </div>
                        </div>

                        <!-- Descrição -->
                        <div class="col-12">
                            <label for="metaDescricao" class="form-label small text-muted fw-medium">Descrição (opcional)</label>
                            <textarea class="form-control rounded-3 shadow-sm border-0" 
                                      id="metaDescricao" rows="4" placeholder="Descreva sua meta financeira"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-0 p-3">
                <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Cancelar
                </button>
                <button type="button" class="btn btn-roxo fw-medium shadow-sm" id="btnSalvarMeta">
                    <i class="fas fa-save me-2"></i> Salvar Meta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Visualização de Meta -->
<div class="modal fade" id="detalheModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold text-roxo">
                    <i class="fas fa-bullseye me-2"></i> Detalhes da Meta
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body p-4 pt-0">
                <div id="metaDetalhes">
                    <div class="d-flex align-items-center mb-4">
                        <div class="meta-icon me-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px; background-color: #f0f0f0;">
                            <i id="detalheIcone" class="fas fa-star" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0" id="detalheTitulo"></h5>
                            <p class="text-muted mb-0" id="detalheCategoria">Sem categoria</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Progresso</h6>
                        <div class="progress mb-2" style="height: 10px;">
                            <div class="progress-bar bg-success" id="detalheProgressoBar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span id="detalheProgresso">0%</span>
                            <span id="detalheValores">R$ 0,00 / R$ 0,00</span>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block">Data de início</small>
                                <strong id="detalheDataInicio">01/01/2023</strong>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block">Data alvo</small>
                                <strong id="detalheDataAlvo">31/12/2023</strong>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Descrição</h6>
                        <p id="detalheDescricao" class="mb-0 text-muted fst-italic">Sem descrição.</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Status</h6>
                        <span class="badge" id="detalheStatus">Em andamento</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 p-3">
                <button type="button" class="btn btn-light fw-medium" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Fechar
                </button>
                <button type="button" class="btn btn-roxo fw-medium shadow-sm" id="btnEditarDetalhe">
                    <i class="fas fa-edit me-2"></i> Editar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="modalConfirmacao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle text-danger fa-3x"></i>
                </div>
                <h5 class="fw-bold mb-3">Confirmar Exclusão</h5>
                <p class="mb-4 text-muted">Tem certeza que deseja excluir esta meta?</p>
                <p class="mb-4 small fw-medium text-danger">Esta ação não pode ser desfeita.</p>
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
<!-- Select2 CSS e JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

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

/* Estilos para o modal e formulários */
.form-control:focus, .form-select:focus {
    border-color: var(--roxo-light);
    box-shadow: 0 0 0 0.25rem rgba(105, 57, 118, 0.25);
}

.form-control, .form-select {
    background-color: #f9f9f9;
}

/* Estilos para o seletor de ícones */
.btn-icon {
    width: 40px;
    height: 40px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border: 1px solid rgba(0,0,0,.1);
    border-radius: 8px;
    color: #495057;
    font-size: 1.2rem;
    transition: all 0.2s;
}

.btn-icon:hover {
    background-color: rgba(105, 57, 118, 0.1);
    color: var(--roxo-primary);
    transform: translateY(-2px);
}

.btn-icon.active {
    background-color: var(--roxo-primary);
    color: white;
    box-shadow: 0 2px 5px rgba(105, 57, 118, 0.3);
}

.icon-preview {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
}
</style>

<script>
    $(document).ready(function() {
        // Variáveis globais
        let metaIdParaExcluir = null;
        
        console.log('Inicializando módulo de metas financeiras');
        
        // Inicializar datepicker
        $('.date-input').flatpickr({
            dateFormat: "Y-m-d",
            locale: "pt",
            minDate: "today",
            altInput: true,
            altFormat: "d/m/Y",
            allowInput: true
        });
        
        // Inicializar Select2 para o seletor de categorias no modal
        $('.select2-modal').select2({
            theme: 'bootstrap-5',
            placeholder: 'Selecione uma categoria',
            allowClear: true,
            dropdownParent: $('#modalMeta'),
            width: '100%'
        });
        
        console.log('Select2 e DatePicker inicializados');
        
        // Seletor de ícones
        $('.btn-icon').click(function() {
            const icon = $(this).data('icon');
            const iconClass = 'fas ' + icon;
            
            // Atualizar campo oculto
            $('#metaIcone').val(icon);
            
            // Atualizar visualização
            $('.icon-preview i').attr('class', iconClass);
            
            // Ativar botão selecionado e desativar os outros
            $('.btn-icon').removeClass('active');
            $(this).addClass('active');
            
            console.log('Ícone selecionado:', icon);
        });
        
        // Atualizar ícone selecionado quando o modal for aberto para edição
        function atualizarIconeSelecionado(icone) {
            if (!icone) return;
            
            // Atualizar campo oculto
            $('#metaIcone').val(icone);
            
            // Atualizar visualização
            $('.icon-preview i').attr('class', 'fas ' + icone);
            
            // Ativar botão correspondente
            $('.btn-icon').removeClass('active');
            $(`.btn-icon[data-icon="${icone}"]`).addClass('active');
            
            console.log('Ícone atualizado:', icone);
        }
        
        // Abrir modal de atualização de progresso
        $(document).on('click', '.btn-action.update', function() {
            const id = $(this).data('id');
            const titulo = $(this).data('title');
            const valorAtual = $(this).data('atual');
            const valorAlvo = $(this).data('alvo');
            const progresso = (valorAlvo > 0) ? Math.min(100, (valorAtual / valorAlvo) * 100) : 0;
            
            $('#metaId').val(id);
            $('#metaTituloProgresso').text(titulo);
            $('#metaValorAtualText').text('R$ ' + valorAtual.toLocaleString('pt-BR', {minimumFractionDigits: 2}));
            $('#metaValorAlvoText').text('R$ ' + valorAlvo.toLocaleString('pt-BR', {minimumFractionDigits: 2}));
            $('#metaProgressoBar').css('width', progresso + '%');
            $('#valorAtual').val(valorAtual);
            
            $('#atualizarModal').modal('show');
        });
        
        // Visualizar detalhes da meta
        $(document).on('click', '.btn-action.view', function() {
            const id = $(this).data('id');
            visualizarMeta(id);
        });
        
        // Editar meta
        $(document).on('click', '.btn-action.edit', function() {
            const id = $(this).data('id');
            editarMeta(id);
        });
        
        // Editar a partir de detalhes
        $('#btnEditarDetalhe').click(function() {
            $('#detalheModal').modal('hide');
            const id = $(this).data('id');
            editarMeta(id);
        });
        
        // Modal de nova meta
        $('#modalMeta').on('show.bs.modal', function (e) {
            console.log('Modal aberto', e.relatedTarget);
            
            if (!e.relatedTarget) return; // Não redefinir quando for para editar
            
            // Limpar formulário quando abrir para nova meta
            $('#metaForm')[0].reset();
            $('#metaIdEdit').val('');
            $('#modalMetaLabel').html('<i class="fas fa-plus-circle me-2"></i> Nova Meta Financeira');
            $('#metaStatusContainer').hide();
            
            // Definir data atual usando flatpickr
            setTimeout(() => {
                const dataPicker = document.querySelector("#metaDataAlvo")._flatpickr;
                if (dataPicker) {
                    const dataFutura = new Date();
                    dataFutura.setMonth(dataFutura.getMonth() + 3); // Meta para 3 meses no futuro como padrão
                    dataPicker.setDate(dataFutura);
                }
            }, 100);
        });
        
        // Salvar meta
        $('#btnSalvarMeta').click(function() {
            console.log('Botão Salvar Meta clicado');
            
            // Validação rápida no cliente
            const titulo = $('#metaTitulo').val();
            const valorAlvo = $('#metaValorAlvo').val();
            const dataAlvo = $('#metaDataAlvo').val();
            
            if (!titulo || !valorAlvo || !dataAlvo) {
                console.error('Campos obrigatórios faltando', { titulo, valorAlvo, dataAlvo });
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos obrigatórios',
                    text: 'Por favor, preencha todos os campos obrigatórios (Título, Valor Alvo e Data Alvo)'
                });
                return;
            }
            
            // Adicionar loading ao botão
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');
            $btn.prop('disabled', true);
            
            salvarMeta().then(() => {
                // Restaurar botão após operação
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            }).catch(() => {
                // Restaurar botão em caso de erro
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            });
        });
        
        // Atualizar progresso
        $('#btnSalvarProgresso').click(function() {
            // Adicionar loading ao botão
            const $btn = $(this);
            const originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...');
            $btn.prop('disabled', true);
            
            atualizarProgresso().then(() => {
                // Restaurar botão após operação
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            }).catch(() => {
                // Restaurar botão em caso de erro
                $btn.html(originalHtml);
                $btn.prop('disabled', false);
            });
        });
        
        // Função para visualizar meta
        async function visualizarMeta(id) {
            try {
                const response = await $.ajax({
                    url: `<?= site_url('metas/detalhes/') ?>${id}`,
                    type: 'GET',
                    dataType: 'json'
                });
                
                if (response.success) {
                    const meta = response.meta;
                    
                    // Preencher dados do modal de detalhes
                    $('#detalheTitulo').text(meta.titulo);
                    $('#detalheCategoria').text(meta.categoria_nome || 'Sem categoria');
                    
                    // Ícone
                    if (meta.icone) {
                        $('#detalheIcone').attr('class', 'fas ' + meta.icone);
                        if (meta.cor) {
                            $('#detalheIcone').css('color', meta.cor);
                            $('.meta-icon').css('background-color', meta.cor + '20'); // cor com transparência
                        }
                    } else {
                        $('#detalheIcone').attr('class', 'fas fa-bullseye');
                        $('.meta-icon').css('background-color', '#f0f0f0');
                    }
                    
                    // Progresso
                    const progresso = meta.progresso;
                    $('#detalheProgressoBar').css('width', progresso + '%');
                    $('#detalheProgresso').text(progresso.toFixed(1) + '%');
                    $('#detalheValores').text(`R$ ${parseFloat(meta.valor_atual).toLocaleString('pt-BR', {minimumFractionDigits: 2})} / R$ ${parseFloat(meta.valor_alvo).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`);
                    
                    // Datas
                    $('#detalheDataInicio').text(formatarData(meta.data_inicio));
                    $('#detalheDataAlvo').text(formatarData(meta.data_alvo));
                    
                    // Descrição
                    $('#detalheDescricao').text(meta.descricao || 'Sem descrição.');
                    
                    // Status
                    const $status = $('#detalheStatus');
                    $status.removeClass('bg-warning bg-success bg-danger');
                    
                    if (meta.status === 'em_andamento') {
                        $status.addClass('bg-warning').text('Em andamento');
                    } else if (meta.status === 'concluida') {
                        $status.addClass('bg-success').text('Concluída');
                    } else if (meta.status === 'cancelada') {
                        $status.addClass('bg-danger').text('Cancelada');
                    }
                    
                    // Guardar ID para edição
                    $('#btnEditarDetalhe').data('id', meta.id);
                    
                    // Exibir modal
                    $('#detalheModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: response.message || 'Não foi possível carregar os detalhes da meta'
                    });
                }
            } catch (error) {
                console.error('Erro ao carregar meta:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro ao carregar os detalhes da meta'
                });
            }
        }
        
        // Função para editar meta
        async function editarMeta(id) {
            try {
                const response = await $.ajax({
                    url: `<?= site_url('metas/detalhes/') ?>${id}`,
                    type: 'GET',
                    dataType: 'json'
                });
                
                if (response.success) {
                    const meta = response.meta;
                    
                    // Atualizar título do modal
                    $('#modalMetaLabel').html('<i class="fas fa-edit me-2"></i> Editar Meta');
                    
                    // Preencher o formulário
                    $('#metaIdEdit').val(meta.id);
                    $('#metaTitulo').val(meta.titulo);
                    $('#metaDescricao').val(meta.descricao);
                    $('#metaValorAlvo').val(meta.valor_alvo);
                    $('#metaValorAtual').val(meta.valor_atual);
                    $('#metaIcone').val(meta.icone);
                    $('#metaCor').val(meta.cor || '#007bff');
                    
                    // Exibir campo de status para edição
                    $('#metaStatusContainer').show();
                    $('#metaStatus').val(meta.status);
                    
                    // Selecionar categoria
                    if (meta.categoria_id) {
                        $('#metaCategoria').val(meta.categoria_id).trigger('change');
                    } else {
                        $('#metaCategoria').val('').trigger('change');
                    }
                    
                    // Definir data usando flatpickr
                    setTimeout(() => {
                        const dataPicker = document.querySelector("#metaDataAlvo")._flatpickr;
                        if (dataPicker) {
                            dataPicker.setDate(meta.data_alvo);
                        }
                        
                        // Atualizar ícone selecionado
                        atualizarIconeSelecionado(meta.icone);
                    }, 100);
                    
                    // Exibir modal
                    $('#modalMeta').modal('show');
                    
                    console.log('Modal editarMeta aberto', {
                        id: meta.id,
                        título: meta.titulo,
                        categoria: meta.categoria_id,
                        status: meta.status,
                        data: meta.data_alvo
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: response.message || 'Não foi possível carregar os dados da meta'
                    });
                }
            } catch (error) {
                console.error('Erro ao carregar meta para edição:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro ao carregar os dados da meta: ' + (error.responseText || error.message || 'Erro desconhecido')
                });
            }
        }
        
        // Função para salvar meta (nova ou edição)
        async function salvarMeta() {
            // Validar campos obrigatórios
            const titulo = $('#metaTitulo').val();
            const valorAlvo = $('#metaValorAlvo').val();
            const dataAlvo = $('#metaDataAlvo').val();
            
            console.log('Dados do formulário:', {
                titulo: titulo,
                valorAlvo: valorAlvo,
                dataAlvo: dataAlvo,
                valorAtual: $('#metaValorAtual').val(),
                categoriaId: $('#metaCategoria').val(),
                descricao: $('#metaDescricao').val(),
                icone: $('#metaIcone').val(),
                cor: $('#metaCor').val()
            });
            
            if (!titulo || !valorAlvo || !dataAlvo) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos obrigatórios',
                    text: 'Por favor, preencha todos os campos obrigatórios (Título, Valor Alvo e Data Alvo)'
                });
                return Promise.reject();
            }
            
            // Preparar dados
            const id = $('#metaIdEdit').val();
            const isEdicao = id !== '';
            
            const dados = {
                titulo: titulo,
                valor_alvo: valorAlvo,
                data_alvo: dataAlvo,
                valor_atual: $('#metaValorAtual').val() || 0,
                categoria_id: $('#metaCategoria').val() || '',
                descricao: $('#metaDescricao').val() || '',
                icone: $('#metaIcone').val() || '',
                cor: $('#metaCor').val() || '#007bff'
            };
            
            // Adicionar status apenas se for edição
            if (isEdicao && $('#metaStatus').val()) {
                dados.status = $('#metaStatus').val();
            }
            
            try {
                const url = isEdicao 
                    ? `<?= site_url('metas/editar/') ?>${id}`
                    : `<?= site_url('metas/nova') ?>`;
                
                console.log('Enviando dados:', dados);
                console.log('URL:', url);
                
                const response = await $.ajax({
                    url: url,
                    type: 'POST',
                    data: dados,
                    dataType: 'json'
                });
                
                console.log('Resposta recebida:', response);
                
                if (response.success) {
                    // Fechar modal
                    $('#modalMeta').modal('hide');
                    
                    // Exibir mensagem de sucesso
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        // Recarregar a página para mostrar os dados atualizados
                        window.location.reload();
                    });
                    
                    return Promise.resolve();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: response.message || 'Erro ao salvar a meta'
                    });
                    return Promise.reject();
                }
            } catch (error) {
                console.error('Erro ao salvar meta:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro ao processar sua solicitação. Detalhes: ' + (error.responseText || error.message || 'Erro desconhecido')
                });
                return Promise.reject();
            }
        }
        
        // Função para atualizar progresso da meta
        async function atualizarProgresso() {
            const id = $('#metaId').val();
            const valorAtual = $('#valorAtual').val();
            
            if (!valorAtual) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo obrigatório',
                    text: 'Por favor, informe o valor atual'
                });
                return Promise.reject();
            }
            
            try {
                const response = await $.ajax({
                    url: `<?= site_url('metas/atualizar/') ?>${id}`,
                    type: 'POST',
                    data: {
                        valor_atual: valorAtual
                    },
                    dataType: 'json'
                });
                
                if (response.success) {
                    // Fechar modal
                    $('#atualizarModal').modal('hide');
                    
                    // Exibir mensagem de sucesso
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        // Recarregar a página para mostrar os dados atualizados
                        window.location.reload();
                    });
                    
                    return Promise.resolve();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: response.message || 'Erro ao atualizar o progresso da meta'
                    });
                    return Promise.reject();
                }
            } catch (error) {
                console.error('Erro ao atualizar progresso:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro ao processar sua solicitação'
                });
                return Promise.reject();
            }
        }
        
        // Função para formatar data (YYYY-MM-DD para DD/MM/YYYY)
        function formatarData(dataStr) {
            if (!dataStr) return '';
            const data = new Date(dataStr);
            return data.toLocaleDateString('pt-BR');
        }
    });
</script>
<?= $this->endSection() ?> 