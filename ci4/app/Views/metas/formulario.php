<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-roxo mb-0">
            <i class="fas fa-bullseye me-2"></i><?= isset($meta) ? 'Editar Meta' : 'Nova Meta Financeira' ?>
        </h1>
        <a href="<?= site_url('metas') ?>" class="btn btn-light shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Voltar
        </a>
    </div>

    <div class="card border-0 shadow-sm hover-card mb-4">
        <div class="card-header bg-white p-4 border-0">
            <h5 class="mb-0 fw-semibold text-roxo">
                <i class="fas fa-pen me-2"></i> <?= isset($meta) ? 'Atualizar Dados da Meta' : 'Criar Nova Meta' ?>
            </h5>
        </div>
        <div class="card-body p-4">
            <?php if (session()->has('error')) : ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= session('error') ?>
                </div>
            <?php endif; ?>

            <form action="<?= isset($meta) ? site_url('metas/editar/' . $meta['id']) : site_url('metas/nova') ?>" method="post">
                <div class="row g-4">
                    <!-- Título -->
                    <div class="col-md-6">
                        <label for="titulo" class="form-label small text-muted fw-medium">Título da Meta *</label>
                        <input type="text" class="form-control form-control-lg rounded-3 shadow-sm border-0" 
                               id="titulo" name="titulo" 
                               value="<?= isset($meta) ? $meta['titulo'] : old('titulo') ?>" 
                               required>
                    </div>

                    <!-- Categoria -->
                    <div class="col-md-6">
                        <label for="categoria_id" class="form-label small text-muted fw-medium">Categoria (opcional)</label>
                        <select class="select2-basic form-select form-select-lg rounded-3 shadow-sm border-0" 
                                id="categoria_id" name="categoria_id">
                            <option value="">Sem categoria</option>
                            <?php foreach ($categorias as $categoria) : ?>
                                <option value="<?= $categoria['id'] ?>" 
                                    <?= (isset($meta) && $meta['categoria_id'] == $categoria['id']) ? 'selected' : '' ?>>
                                    <?= $categoria['nome'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Valor Alvo -->
                    <div class="col-md-6">
                        <label for="valor_alvo" class="form-label small text-muted fw-medium">Valor Alvo *</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light fw-medium">R$</span>
                            <input type="number" class="form-control form-control-lg rounded-end shadow-sm border-0" 
                                   id="valor_alvo" name="valor_alvo" 
                                   value="<?= isset($meta) ? $meta['valor_alvo'] : old('valor_alvo') ?>" 
                                   min="0.01" step="0.01" required>
                        </div>
                    </div>

                    <!-- Valor Atual -->
                    <div class="col-md-6">
                        <label for="valor_atual" class="form-label small text-muted fw-medium">Valor Atual</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light fw-medium">R$</span>
                            <input type="number" class="form-control form-control-lg rounded-end shadow-sm border-0" 
                                   id="valor_atual" name="valor_atual" 
                                   value="<?= isset($meta) ? $meta['valor_atual'] : old('valor_atual', 0) ?>" 
                                   min="0" step="0.01">
                        </div>
                    </div>

                    <!-- Data Alvo -->
                    <div class="col-md-6">
                        <label for="data_alvo" class="form-label small text-muted fw-medium">Data Alvo *</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light"><i class="fas fa-calendar-alt"></i></span>
                            <input type="text" class="form-control form-control-lg rounded-end shadow-sm border-0 date-input" 
                                   id="data_alvo" name="data_alvo" 
                                   value="<?= isset($meta) ? $meta['data_alvo'] : old('data_alvo') ?>" 
                                   required>
                        </div>
                    </div>

                    <!-- Status (apenas para edição) -->
                    <?php if (isset($meta)) : ?>
                    <div class="col-md-6">
                        <label for="status" class="form-label small text-muted fw-medium">Status</label>
                        <select class="form-select form-select-lg rounded-3 shadow-sm border-0" id="status" name="status">
                            <option value="em_andamento" <?= $meta['status'] == 'em_andamento' ? 'selected' : '' ?>>Em andamento</option>
                            <option value="concluida" <?= $meta['status'] == 'concluida' ? 'selected' : '' ?>>Concluída</option>
                            <option value="cancelada" <?= $meta['status'] == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                        </select>
                    </div>
                    <?php endif; ?>

                    <!-- Ícone -->
                    <div class="col-md-6">
                        <label for="icone" class="form-label small text-muted fw-medium">Ícone (opcional)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light"><i class="fas fa-icons"></i></span>
                            <input type="text" class="form-control form-control-lg rounded-end shadow-sm border-0" 
                                   id="icone" name="icone" 
                                   value="<?= isset($meta) ? $meta['icone'] : old('icone') ?>" 
                                   placeholder="Ex: fa-home">
                        </div>
                        <small class="text-muted">Use classes de ícones do Font Awesome (ex: fa-home, fa-car)</small>
                    </div>

                    <!-- Cor -->
                    <div class="col-md-6">
                        <label for="cor" class="form-label small text-muted fw-medium">Cor (opcional)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text border-0 bg-light"><i class="fas fa-palette"></i></span>
                            <input type="color" class="form-control form-control-lg rounded-end shadow-sm border-0 form-control-color" 
                                   id="cor" name="cor" 
                                   value="<?= isset($meta) ? $meta['cor'] : old('cor', '#007bff') ?>">
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="col-12">
                        <label for="descricao" class="form-label small text-muted fw-medium">Descrição (opcional)</label>
                        <textarea class="form-control rounded-3 shadow-sm border-0" 
                                  id="descricao" name="descricao" rows="4"><?= isset($meta) ? $meta['descricao'] : old('descricao') ?></textarea>
                    </div>
                    
                    <!-- Botões de Ação -->
                    <div class="col-12 d-flex justify-content-end mt-4">
                        <a href="<?= site_url('metas') ?>" class="btn btn-light fw-medium me-2">
                            <i class="fas fa-times me-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-roxo fw-medium shadow-sm">
                            <i class="fas fa-save me-2"></i> <?= isset($meta) ? 'Atualizar Meta' : 'Criar Meta' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Inicializar datepicker para o campo de data alvo
        $('.date-input').flatpickr({
            dateFormat: "Y-m-d",
            locale: "pt",
            minDate: "today",
            altInput: true,
            altFormat: "d/m/Y",
            allowInput: true
        });
        
        // Inicializar Select2 para categorias
        $('.select2-basic').select2({
            theme: 'bootstrap-5',
            placeholder: 'Selecione uma categoria',
            allowClear: true,
            width: '100%'
        });
    });
</script>
<?= $this->endSection() ?> 