<?= $this->extend('layout/template') ?>

<?= $this->section('styles') ?>
<style>
    /* Cores padrão FinanceBye */
    :root {
        --roxo-primary: #7b68ee;
        --roxo-light: rgba(123, 104, 238, 0.1);
        --roxo-hover: #6a5acd;
        --green-primary: #28a745;
        --green-light: rgba(40, 167, 69, 0.1);
        --red-primary: #dc3545;
        --red-light: rgba(220, 53, 69, 0.1);
    }
    
    /* Classes de cores do FinanceBye */
    .bg-roxo {
        background-color: var(--roxo-primary) !important;
    }
    .bg-roxo-light {
        background-color: var(--roxo-light) !important;
    }
    .text-roxo {
        color: var(--roxo-primary) !important;
    }
    .btn-roxo {
        background-color: var(--roxo-primary) !important;
        color: #fff !important;
        border-color: var(--roxo-primary) !important;
    }
    .btn-roxo:hover {
        background-color: var(--roxo-hover) !important;
        border-color: var(--roxo-hover) !important;
    }
    .btn-outline-roxo {
        color: var(--roxo-primary) !important;
        border-color: var(--roxo-primary) !important;
        background-color: transparent !important;
    }
    .btn-outline-roxo:hover {
        color: #fff !important;
        background-color: var(--roxo-primary) !important;
    }
    .bg-purple {
        background-color: var(--roxo-primary) !important;
    }
    .bg-purple-subtle {
        background-color: var(--roxo-light) !important;
    }
    .text-purple {
        color: var(--roxo-primary) !important;
    }
    .btn-outline-purple {
        color: var(--roxo-primary) !important;
        border-color: var(--roxo-primary) !important;
    }
    .btn-outline-purple:hover {
        color: #fff !important;
        background-color: var(--roxo-primary) !important;
    }
    
    /* Estilos para o calendário básico */
    .basic-calendar {
        font-family: 'Nunito', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .calendar-cell {
        cursor: pointer;
        height: 100%;
        min-height: 40px;
        transition: all 0.2s ease;
    }
    
    .calendar-cell:hover {
        background-color: var(--roxo-light);
    }
    
    .dia-numero {
        font-weight: 500;
    }
    
    /* Estilos melhorados para o dia selecionado */
    .selecionado {
        background-color: var(--roxo-primary) !important;
        color: white !important;
        position: relative;
        z-index: 1;
        box-shadow: 0 0 8px rgba(123, 104, 238, 0.5);
        transform: scale(1.05);
        border-radius: 4px;
    }
    
    .selecionado .dia-numero {
        font-weight: bold;
    }
    
    .selecionado .evento-dot {
        opacity: 0.9;
        background-color: white !important;
    }
    
    .selecionado .calendar-cell {
        background-color: var(--roxo-primary) !important;
    }
    
    .evento-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin: 0 1px;
    }
    
    .evento-receita {
        background-color: var(--green-primary);
    }
    
    .evento-despesa {
        background-color: var(--red-primary);
    }
    
    .evento-meta {
        background-color: var(--roxo-primary);
    }
    
    .icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
    
    /* Estilos para os painéis laterais */
    .card {
        transition: all 0.2s ease;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Estilos para tornar responsivo */
    @media (max-width: 768px) {
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .table th, .table td {
            padding: 0.5rem 0.25rem;
        }
        
        .icon-circle {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }
        
        .card-header {
            padding: 0.75rem;
        }
        
        .card-body {
            padding: 0.75rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('head_extra') ?>
<!-- jQuery deve ser carregado antes do Bootstrap -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS necessário para modais e outros componentes -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 para alertas bonitos -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- FullCalendar - Versão 5.11.3 com links atualizados de CDNs alternativos -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
<!-- Adicionar tratamento de erros -->
<script>
// Não permitir que erros interrompam a execução e exibir mensagens mais úteis
window.addEventListener('error', function(e) {
    console.error('Erro detectado:', e.error || e.message);
    // Não interromper a execução normal para dar chance às alternativas
    e.preventDefault();
    return true;
});

// Definir FullCalendar como objeto vazio para evitar erros
if (typeof FullCalendar === 'undefined') {
    window.FullCalendar = {}; 
}
</script>
<!-- Usando o CDN do jsDelivr como principal -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<!-- Usando o CDN do unpkg como backup -->
<script>
// Verificar se o FullCalendar foi carregado corretamente
setTimeout(function() {
    if (typeof FullCalendar === 'undefined' || !FullCalendar.Calendar) {
        console.log('Carregando FullCalendar de CDN alternativo...');
        var script = document.createElement('script');
        script.src = 'https://unpkg.com/fullcalendar@5.11.3/main.min.js';
        script.onload = function() {
            console.log('FullCalendar carregado com sucesso do CDN alternativo');
            // Carregar locale depois do script principal
            var localeScript = document.createElement('script');
            localeScript.src = 'https://unpkg.com/fullcalendar@5.11.3/locales/pt-br.js';
            document.head.appendChild(localeScript);
            
            // Tentar inicializar o calendário
            if (typeof inicializarCalendario === 'function') {
                setTimeout(inicializarCalendario, 500);
            }
        };
        document.head.appendChild(script);
    } else {
        console.log('FullCalendar carregado com sucesso');
    }
}, 1000);
</script>
<!-- Locale para português do Brasil -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/pt-br.js"></script>

<!-- Versão inline (emergência) do FullCalendar para último recurso -->
<script>
// Verificação final - se nada funcionar após 3 segundos, inserir versão básica manualmente
setTimeout(function() {
    if (typeof FullCalendar === 'undefined' || !FullCalendar.Calendar) {
        console.error('Nenhuma versão do FullCalendar foi carregada após tentativas. Inserindo versão mínima...');
        
        // Inserir versão mínima do FullCalendar diretamente
        document.write(`
            <script>
            // Versão mínima do FullCalendar (básico mas suficiente)
            window.FullCalendar = {
                Calendar: function(el, options) {
                    this.el = el;
                    this.options = options || {};
                    this.eventos = [];
                    this.view = { currentStart: new Date() };
                    
                    this.render = function() {
                        // Usar o calendário básico HTML
                        if (typeof usarCalendarioBasico === 'function') {
                            usarCalendarioBasico();
                        }
                    };
                    
                    this.addEventSource = function(eventos) {
                        this.eventos = eventos;
                    };
                    
                    this.removeAllEvents = function() {
                        this.eventos = [];
                    };
                    
                    this.today = function() {
                        const hoje = new Date();
                        this.view.currentStart = hoje;
                        if (typeof renderizarMes === 'function') {
                            renderizarMes(hoje.getMonth(), hoje.getFullYear());
                        } else {
                            usarCalendarioBasico();
                        }
                    };
                    
                    this.prev = function() {
                        const mesAtual = parseInt(document.getElementById('mes-atual').getAttribute('data-mes') || 0);
                        const anoAtual = parseInt(document.getElementById('mes-atual').getAttribute('data-ano') || new Date().getFullYear());
                        
                        if (typeof renderizarMes === 'function') {
                            renderizarMes(mesAtual - 1, anoAtual);
                        }
                    };
                    
                    this.next = function() {
                        const mesAtual = parseInt(document.getElementById('mes-atual').getAttribute('data-mes') || 0);
                        const anoAtual = parseInt(document.getElementById('mes-atual').getAttribute('data-ano') || new Date().getFullYear());
                        
                        if (typeof renderizarMes === 'function') {
                            renderizarMes(mesAtual + 1, anoAtual);
                        }
                    };
                }
            };
            <\/script>
        `);
    }
}, 3000);
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Calendário Financeiro</h1>
        <a href="<?= site_url('transacoes/adicionar') ?>" class="btn btn-roxo">
            <i class="fas fa-plus me-1"></i>Nova Transação
        </a>
    </div>
    
    <div class="row">
        <!-- Calendário Principal -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-white p-3 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0" id="titulo-calendario">Calendário</h5>
                        <div class="btn-group">
                            <button id="btn-anterior" type="button" class="btn btn-sm btn-light border">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button id="btn-hoje" type="button" class="btn btn-sm btn-roxo">
                                <i class="fas fa-calendar-day me-1"></i>Hoje
                            </button>
                            <button id="btn-proximo" type="button" class="btn btn-sm btn-light border">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <h6 class="text-muted mt-2 mb-0" id="periodoAtual"></h6>
                </div>
                <div class="card-body p-0">
                    <div id="calendario-container">
                        <div id="calendario-vanilla"></div>
                    </div>
                </div>
            </div>
            
            <!-- Resumo Financeiro -->
            <div id="resumo-financeiro" class="mb-4">
                <!-- Preenchido via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Modal para Detalhes do Evento -->
<div class="modal fade" id="eventoModal" tabindex="-1" aria-labelledby="eventoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="eventoModalLabel">Detalhes do Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body" id="eventoModalBody">
                <!-- Conteúdo preenchido via JavaScript -->
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Objeto global para armazenar eventos
let todosEventos = [];
// Referência ao calendário
let calendario = null;

// Função para formatar moeda
function formatCurrency(value) {
    return parseFloat(value).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Formatar data para exibição
function formatarData(data) {
    if (!data || !(data instanceof Date) || isNaN(data.getTime())) {
        console.error('Data inválida:', data);
        return 'Data inválida';
    }
    
    const dia = data.getDate().toString().padStart(2, '0');
    const mes = (data.getMonth() + 1).toString().padStart(2, '0');
    const ano = data.getFullYear();
    
    return `${dia}/${mes}/${ano}`;
}

// Formatar data para API
function formatarDataAPI(data) {
    if (!data || !(data instanceof Date) || isNaN(data.getTime())) {
        console.error('Data inválida para API:', data);
        return '';
    }
    
    const dia = data.getDate().toString().padStart(2, '0');
    const mes = (data.getMonth() + 1).toString().padStart(2, '0');
    const ano = data.getFullYear();
    
    return `${ano}-${mes}-${dia}`;
}

// Processamento de dados financeiros (mantido igual)
function processarDadosFinanceiros(dados) {
    let totalReceitas = 0;
    let totalDespesas = 0;
    const categorias = {};
    
    dados.forEach(evento => {
        if (evento.extendedProps.tipo === 'transacao') {
            const valor = parseFloat(evento.extendedProps.valor);
            
            if (valor >= 0) {
                totalReceitas += valor;
            } else {
                totalDespesas += Math.abs(valor);
            }
            
            const categoria = evento.extendedProps.categoria || 'Sem categoria';
            if (!categorias[categoria]) {
                categorias[categoria] = {
                    total: 0,
                    tipo: valor >= 0 ? 'receita' : 'despesa'
                };
            }
            
            categorias[categoria].total += Math.abs(valor);
        }
    });
    
    document.getElementById('totalReceitas').textContent = `R$ ${formatCurrency(totalReceitas)}`;
    document.getElementById('totalDespesas').textContent = `R$ ${formatCurrency(totalDespesas)}`;
    document.getElementById('saldoMes').textContent = `R$ ${formatCurrency(totalReceitas - totalDespesas)}`;
    
    atualizarCategoriasPrincipais(categorias);
}

// Atualização de categorias principais (mantido igual)
function atualizarCategoriasPrincipais(categorias) {
    const categoriasEl = document.getElementById('categoriasPrincipais');
    
    if (Object.keys(categorias).length === 0) {
        categoriasEl.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-info-circle mb-2"></i>
                <p class="mb-0">Nenhuma transação no período.</p>
            </div>
        `;
        return;
    }
    
    const categoriasSorted = Object.entries(categorias).sort((a, b) => b[1].total - a[1].total);
    
    const categoriasTop = categoriasSorted.slice(0, 5);
    
    const totalGeral = categoriasSorted.reduce((acc, curr) => acc + curr[1].total, 0);
    
    let html = '';
    
    categoriasTop.forEach(([nome, dados]) => {
        const percentagem = (dados.total / totalGeral * 100).toFixed(1);
        const corClasse = dados.tipo === 'receita' ? 'success' : 'danger';
        
        html += `
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="categoria-badge bg-${corClasse}-subtle text-${corClasse}">
                        ${nome}
                    </span>
                    <span class="text-${corClasse} fw-bold">
                        R$ ${formatCurrency(dados.total)}
                    </span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-${corClasse}" 
                         role="progressbar" 
                         style="width: ${percentagem}%" 
                         aria-valuenow="${percentagem}" 
                         aria-valuemin="0" 
                         aria-valuemax="100"></div>
                </div>
                <div class="text-end mt-1">
                    <small class="text-muted">${percentagem}% do total</small>
                </div>
            </div>
        `;
    });
    
    categoriasEl.innerHTML = html;
}

// Atualização de próximos eventos
function atualizarProximosEventos(dados) {
    const proximosEventosEl = document.getElementById('proximos-eventos');
    
    if (!dados || dados.length === 0) {
        proximosEventosEl.innerHTML = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-calendar-check mb-2"></i>
                <p class="mb-0">Nenhum evento no período.</p>
            </div>
        `;
        return;
    }
    
    const hoje = new Date();
    hoje.setHours(0, 0, 0, 0);
    
    // Filtra apenas eventos válidos e futuros
    const eventosFuturos = dados.filter(evento => {
        if (!evento || !evento.start || !evento.extendedProps) return false;
        
        const dataEvento = new Date(evento.start);
        dataEvento.setHours(0, 0, 0, 0);
        return dataEvento >= hoje;
    });
    
    eventosFuturos.sort((a, b) => new Date(a.start) - new Date(b.start));
    
    const eventosPorData = {};
    eventosFuturos.forEach(evento => {
        const dataStr = formatarData(evento.start);
        if (!eventosPorData[dataStr]) {
            eventosPorData[dataStr] = [];
        }
        eventosPorData[dataStr].push(evento);
    });
    
    const datasProximas = Object.keys(eventosPorData).slice(0, 10);
    
    let html = '';
    
    datasProximas.forEach(data => {
        html += `
            <div class="px-4 py-2 bg-light border-bottom fw-medium">
                <i class="fas fa-calendar-day me-2"></i>${data}
            </div>
            <div class="eventos-do-dia">
        `;
        
        eventosPorData[data].forEach((evento, index) => {
            let iconeClasse = 'fa-star';
            let corClasse = 'primary';
            let titulo = evento.title || 'Evento';
            
            if (evento.extendedProps.tipo === 'transacao') {
                if (evento.extendedProps.valor >= 0) {
                    iconeClasse = 'fa-arrow-up';
                    corClasse = 'success';
                } else {
                    iconeClasse = 'fa-arrow-down';
                    corClasse = 'danger';
                }
            } else if (evento.extendedProps.tipo === 'meta') {
                iconeClasse = 'fa-bullseye';
                corClasse = 'purple';
            }
            
            html += `
                <div class="p-3 border-bottom evento-item evento-clicavel" 
                     role="button" 
                     data-evento-id="${evento.id}"
                     data-evento-index="${index}"
                     data-evento-data="${data}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-${corClasse}-subtle me-3">
                                <i class="fas ${iconeClasse} text-${corClasse}"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-medium">${titulo}</h6>
                                <small class="text-muted">
                                    ${evento.extendedProps.categoria || 'Sem categoria'}
                                </small>
                            </div>
                        </div>
                        
                        ${evento.extendedProps.tipo === 'transacao' ? 
                            `<span class="badge bg-${corClasse}-subtle text-${corClasse} px-3 py-2">
                                R$ ${formatCurrency(Math.abs(evento.extendedProps.valor))}
                            </span>`
                            : 
                            `<span class="badge bg-purple-subtle text-purple px-3 py-2">
                                ${parseFloat(evento.extendedProps.progresso).toFixed(0)}%
                            </span>`
                        }
                    </div>
                </div>
            `;
        });
        
        html += `</div>`;
    });
    
    if (datasProximas.length === 0) {
        html = `
            <div class="text-center py-4 text-muted">
                <i class="fas fa-calendar-check mb-2"></i>
                <p class="mb-0">Nenhum evento futuro no período.</p>
            </div>
        `;
    }
    
    proximosEventosEl.innerHTML = html;
    
    // Adicionar listeners de evento após renderizar o HTML
    document.querySelectorAll('.evento-clicavel').forEach(item => {
        item.addEventListener('click', function() {
            const eventoId = this.getAttribute('data-evento-id');
            const eventoData = this.getAttribute('data-evento-data');
            const eventoIndex = parseInt(this.getAttribute('data-evento-index'));
            
            // Encontrar o evento nos dados
            const evento = eventosPorData[eventoData][eventoIndex];
            if (evento) {
                exibirDetalheEvento(evento);
            }
        });
    });
}

// Função para exibir detalhes do evento
function exibirDetalheEvento(evento) {
    if (!evento) {
        console.error('Evento inválido:', evento);
        return;
    }
    
    console.log('Exibindo detalhes do evento:', evento);
    
    // Referência ao modal
    const modal = document.getElementById('eventoModal');
    const modalTitle = document.getElementById('eventoModalLabel');
    const modalBody = document.getElementById('eventoModalBody');
    
    if (!modal || !modalTitle || !modalBody) {
        console.error('Elementos do modal não encontrados!');
        return;
    }
    
    const tipo = evento.extendedProps?.tipo || 'outro';
    const titulo = evento.title || 'Evento sem título';
    
    // Configurar o título do modal
    modalTitle.textContent = titulo;
    
    // Construir o conteúdo baseado no tipo de evento
    let conteudo = '';
    
    if (tipo === 'transacao') {
        const valor = parseFloat(evento.extendedProps.valor || 0);
        const descricao = evento.extendedProps.descricao || 'Sem descrição';
        const categoria = evento.extendedProps.categoria || 'Sem categoria';
        const dataEvento = new Date(evento.start);
        const dataFormatada = formatarData(dataEvento);
        
        const ehReceita = valor >= 0;
        const classe = ehReceita ? 'success' : 'danger';
        const icone = ehReceita ? 'arrow-up' : 'arrow-down';
        const tipoTexto = ehReceita ? 'Receita' : 'Despesa';
        
        conteudo = `
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-circle bg-${classe}-subtle me-3" style="width: 42px; height: 42px; font-size: 1.2rem;">
                        <i class="fas fa-${icone}"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">${tipoTexto}</h6>
                        <h4 class="fw-bold text-${classe} mb-0">R$ ${formatCurrency(Math.abs(valor))}</h4>
                    </div>
                </div>
                
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Data</label>
                            <div class="fw-medium">${dataFormatada}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Categoria</label>
                            <div class="fw-medium">${categoria}</div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small">Descrição</label>
                    <div>${descricao}</div>
                </div>
            </div>
            
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fechar
                </button>
                <a href="<?= site_url('transacoes/editar') ?>/${evento.id}" class="btn btn-outline-primary">
                    <i class="fas fa-edit me-1"></i>Editar
                </a>
                <button type="button" class="btn btn-outline-danger" onclick="confirmarExclusao(${evento.id}, '${titulo}')">
                    <i class="fas fa-trash me-1"></i>Excluir
                </button>
            </div>
        `;
    } else if (tipo === 'meta') {
        const valor = parseFloat(evento.extendedProps.valor || 0);
        const valorAtual = parseFloat(evento.extendedProps.valorAtual || 0);
        const progresso = parseFloat(evento.extendedProps.progresso || 0);
        const descricao = evento.extendedProps.descricao || 'Sem descrição';
        const categoria = evento.extendedProps.categoria || 'Sem categoria';
        
        const dataInicio = new Date(evento.start);
        const dataFim = evento.end ? new Date(evento.end) : null;
        
        const dataInicioFormatada = formatarData(dataInicio);
        const dataFimFormatada = dataFim ? formatarData(dataFim) : 'Não definido';
        
        conteudo = `
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-circle bg-purple-subtle me-3" style="width: 42px; height: 42px; font-size: 1.2rem;">
                        <i class="fas fa-bullseye text-purple"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Meta</h6>
                        <h4 class="fw-bold text-purple mb-0">R$ ${formatCurrency(valor)}</h4>
                    </div>
                </div>
                
                <div class="progress mb-2" style="height: 10px;">
                    <div class="progress-bar bg-purple" role="progressbar" style="width: ${progresso}%" 
                         aria-valuenow="${progresso}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="small text-muted">R$ ${formatCurrency(valorAtual)}</span>
                    <span class="small text-purple fw-bold">${progresso}%</span>
                    <span class="small text-muted">R$ ${formatCurrency(valor)}</span>
                </div>
                
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Início</label>
                            <div class="fw-medium">${dataInicioFormatada}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Fim</label>
                            <div class="fw-medium">${dataFimFormatada}</div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small">Categoria</label>
                    <div class="fw-medium">${categoria}</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small">Descrição</label>
                    <div>${descricao}</div>
                </div>
            </div>
            
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fechar
                </button>
                <a href="<?= site_url('metas/editar') ?>/${evento.id}" class="btn btn-outline-primary">
                    <i class="fas fa-edit me-1"></i>Editar
                </a>
                <button type="button" class="btn btn-outline-danger" onclick="confirmarExclusao(${evento.id}, '${titulo}')">
                    <i class="fas fa-trash me-1"></i>Excluir
                </button>
            </div>
        `;
    } else {
        // Evento genérico
        const dataEvento = new Date(evento.start);
        const dataFormatada = formatarData(dataEvento);
        
        conteudo = `
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-circle bg-primary-subtle me-3" style="width: 42px; height: 42px; font-size: 1.2rem;">
                        <i class="fas fa-calendar-day text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-0">Evento</h6>
                        <h4 class="fw-bold text-primary mb-0">${titulo}</h4>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small">Data</label>
                    <div class="fw-medium">${dataFormatada}</div>
                </div>
            </div>
            
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Fechar
                </button>
            </div>
        `;
    }
    
    // Atualizar o conteúdo do modal
    modalBody.innerHTML = conteudo;
    
    // Exibir o modal
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

// Função para confirmar exclusão
function confirmarExclusao(id, titulo) {
    Swal.fire({
        title: 'Confirmar exclusão?',
        text: `Deseja realmente excluir "${titulo}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `<?= site_url('transacoes/excluir') ?>/${id}`;
        }
    });
}

// Forçar o uso do calendário básico automaticamente (já que é o que funciona)
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado. Inicializando calendário básico diretamente...');
    // Usar diretamente o calendário básico sem tentar carregar o FullCalendar
    setTimeout(function() {
        usarCalendarioBasico();
        
        // Selecionar o dia atual por padrão ao inicializar
        const hoje = new Date();
        const dataHoje = formatarDataAPI(hoje);
        if (!window.diaSelecionado) {
            selecionarDiaBasico(dataHoje);
        }
    }, 500);
    
    // Configurar botões de navegação com os IDs corretos
    document.getElementById('btn-anterior').addEventListener('click', function() {
        renderizarMes(window.mesAtual - 1, window.anoAtual);
    });
    
    document.getElementById('btn-hoje').addEventListener('click', function() {
        const hoje = new Date();
        renderizarMes(hoje.getMonth(), hoje.getFullYear());
        // Também selecionar o dia atual ao clicar em "Hoje"
        const dataHoje = formatarDataAPI(hoje);
        selecionarDiaBasico(dataHoje);
    });
    
    document.getElementById('btn-proximo').addEventListener('click', function() {
        renderizarMes(window.mesAtual + 1, window.anoAtual);
    });
});

// Usar um calendário básico HTML 
function usarCalendarioBasico() {
    console.log('Usando calendário básico HTML com estilo FinanceBye');
    const calendarEl = document.getElementById('calendario-vanilla');
    if (!calendarEl) {
        console.error('Elemento do calendário não encontrado!');
        return;
    }
    
    // Data atual
    const hoje = new Date();
    const mes = hoje.getMonth();
    const ano = hoje.getFullYear();
    
    // Nomes de meses e dias
    const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    const diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
    
    // Renderizar o calendário básico em HTML com estilo do FinanceBye
    calendarEl.innerHTML = `
        <div class="basic-calendar">
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-0">
                    <table class="table text-center mb-0">
                        <thead>
                            <tr class="bg-light">
                                ${diasSemana.map(dia => `<th>${dia}</th>`).join('')}
                            </tr>
                        </thead>
                        <tbody id="calendario-corpo">
                            <!-- Preenchido via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="eventos-dia" class="eventos-dia mt-3"></div>
        </div>
    `;
    
    // Variáveis para controle do calendário básico
    window.mesAtual = mes;
    window.anoAtual = ano;
    
    // Carregar dados de exemplo para teste
    todosEventos = [
        {
            id: 1,
            title: 'Aluguel',
            start: '2025-04-05',
            extendedProps: {
                tipo: 'transacao',
                valor: -1200,
                categoria: 'Moradia',
                descricao: 'Aluguel mensal'
            }
        },
        {
            id: 2,
            title: 'Salário',
            start: '2025-04-10',
            extendedProps: {
                tipo: 'transacao',
                valor: 5000,
                categoria: 'Renda',
                descricao: 'Salário mensal'
            }
        },
        {
            id: 3,
            title: 'Farmácia',
            start: '2025-04-15',
            extendedProps: {
                tipo: 'transacao',
                valor: -120,
                categoria: 'Saúde',
                descricao: 'Medicamentos'
            }
        },
        {
            id: 4,
            title: 'Meta de Emergência',
            start: '2025-04-01',
            end: '2025-05-31',
            extendedProps: {
                tipo: 'meta',
                valor: 10000,
                valorAtual: 6000,
                progresso: 60,
                categoria: 'Reserva',
                descricao: 'Fundo de emergência'
            }
        }
    ];
    
    // Função para renderizar o mês
    window.renderizarMes = function(mes, ano) {
        // Ajustar mês e ano se necessário
        if (mes < 0) {
            mes = 11;
            ano--;
        } else if (mes > 11) {
            mes = 0;
            ano++;
        }
        
        window.mesAtual = mes;
        window.anoAtual = ano;
        
        // Atualizar título no cabeçalho e elemento oculto para controle de navegação
        const periodoEl = document.getElementById('periodoAtual');
        if (periodoEl) {
            periodoEl.textContent = `${meses[mes]} de ${ano}`;
            periodoEl.setAttribute('data-mes', mes);
            periodoEl.setAttribute('data-ano', ano);
        }
        
        // Calcular primeiro dia do mês e número de dias
        const primeiroDia = new Date(ano, mes, 1);
        const ultimoDia = new Date(ano, mes + 1, 0);
        const totalDias = ultimoDia.getDate();
        const diaSemanaInicio = primeiroDia.getDay();
        
        // Construir a grade do calendário
        let html = '';
        let dia = 1;
        
        // Criar até 6 semanas (linhas)
        for (let i = 0; i < 6; i++) {
            let semana = '<tr>';
            
            // Criar 7 dias (colunas)
            for (let j = 0; j < 7; j++) {
                if ((i === 0 && j < diaSemanaInicio) || dia > totalDias) {
                    // Célula vazia
                    semana += '<td class="bg-light-subtle"></td>';
                } else {
                    // Verificar se é hoje
                    const dataAtual = new Date(ano, mes, dia);
                    const dataFormatada = formatarDataAPI(dataAtual);
                    const ehHoje = (dia === hoje.getDate() && mes === hoje.getMonth() && ano === hoje.getFullYear());
                    
                    // Verificar se é o dia selecionado
                    const ehSelecionado = window.diaSelecionado === dataFormatada;
                    
                    // Verificar se tem eventos e contar por tipo
                    let receitas = 0;
                    let despesas = 0;
                    let metas = 0;
                    
                    todosEventos.forEach(evento => {
                        if (!evento.start) return;
                        
                        const dataEvento = new Date(evento.start);
                        const eventoFormatado = formatarDataAPI(dataEvento);
                        
                        if (eventoFormatado === dataFormatada) {
                            if (evento.extendedProps.tipo === 'transacao') {
                                if (parseFloat(evento.extendedProps.valor) >= 0) {
                                    receitas++;
                                } else {
                                    despesas++;
                                }
                            } else if (evento.extendedProps.tipo === 'meta') {
                                metas++;
                            }
                        }
                    });
                    
                    const temEventos = receitas > 0 || despesas > 0 || metas > 0;
                    
                    // Definir classes da célula
                    let classesCelula = '';
                    if (ehHoje) classesCelula += ' bg-roxo-light fw-bold';
                    if (ehSelecionado) classesCelula += ' selecionado';
                    if ((j === 0 || j === 6)) classesCelula += ' bg-light-subtle'; // Fim de semana
                    
                    // Criar marcadores visuais para eventos
                    let marcadoresHtml = '';
                    if (temEventos) {
                        marcadoresHtml = '<div class="d-flex justify-content-center mt-1 gap-1">';
                        if (receitas > 0) {
                            marcadoresHtml += `<span class="evento-dot evento-receita" title="${receitas} receita(s)"></span>`;
                        }
                        if (despesas > 0) {
                            marcadoresHtml += `<span class="evento-dot evento-despesa" title="${despesas} despesa(s)"></span>`;
                        }
                        if (metas > 0) {
                            marcadoresHtml += `<span class="evento-dot evento-meta" title="${metas} meta(s)"></span>`;
                        }
                        marcadoresHtml += '</div>';
                    }
                    
                    // Criar a célula com o dia e marcadores
                    semana += `
                        <td class="${classesCelula}" data-data="${dataFormatada}" onclick="selecionarDiaBasico('${dataFormatada}')">
                            <div class="p-2 calendar-cell">
                                <div class="dia-numero">${dia}</div>
                                ${marcadoresHtml}
                            </div>
                        </td>
                    `;
                    dia++;
                }
            }
            
            semana += '</tr>';
            html += semana;
            
            // Sair do loop se já mostrou todos os dias
            if (dia > totalDias) break;
        }
        
        // Atualizar o corpo do calendário
        document.getElementById('calendario-corpo').innerHTML = html;
        
        // Processar dados financeiros do mês
        processarDadosFinanceiros(todosEventos);
        
        // Atualizar próximos eventos
        atualizarProximosEventos(todosEventos);
        
        // Se não houver dia selecionado, selecionar o dia de hoje por padrão
        if (!window.diaSelecionado) {
            const hoje = new Date();
            if (hoje.getMonth() === mes && hoje.getFullYear() === ano) {
                const dataHoje = formatarDataAPI(hoje);
                selecionarDiaBasico(dataHoje);
            }
        }
    };
    
    // Renderizar o mês atual
    renderizarMes(mes, ano);
}

// Função para selecionar um dia no calendário básico
window.selecionarDiaBasico = function(dataStr) {
    console.log('Selecionando dia:', dataStr);
    
    // Remover seleção anterior
    document.querySelectorAll('#calendario-corpo td').forEach(td => {
        td.classList.remove('selecionado');
    });
    
    // Destacar o dia selecionado
    const celula = document.querySelector(`td[data-data="${dataStr}"]`);
    if (celula) {
        celula.classList.add('selecionado');
        // Armazenar o dia selecionado
        window.diaSelecionado = dataStr;
    }
    
    // Filtrar eventos do dia selecionado
    const data = new Date(dataStr);
    const dataFormatada = formatarData(data);
    
    const eventosDia = todosEventos.filter(evento => {
        const dataEvento = new Date(evento.start);
        return formatarDataAPI(dataEvento) === dataStr;
    });
    
    // Exibir eventos do dia
    const eventosDiaEl = document.getElementById('eventos-dia');
    if (!eventosDiaEl) return;
    
    // Se não houver eventos, mostrar mensagem
    if (eventosDia.length === 0) {
        eventosDiaEl.innerHTML = `
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-body text-center p-4">
                    <i class="fas fa-calendar-day text-muted mb-2 fs-3"></i>
                    <p class="mb-2">Nenhum evento em ${dataFormatada}</p>
                    <a href="<?= site_url('transacoes/adicionar') ?>?data=${dataStr}" class="btn btn-sm btn-roxo">
                        <i class="fas fa-plus me-1"></i>Adicionar Transação
                    </a>
                </div>
            </div>
        `;
        return;
    }
    
    // Construir HTML para a lista de eventos
    let html = `
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-white border-0 p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">
                        <i class="fas fa-calendar-day me-2 text-roxo"></i>
                        Eventos em ${dataFormatada}
                    </h6>
                    <a href="<?= site_url('transacoes/adicionar') ?>?data=${dataStr}" class="btn btn-sm btn-roxo">
                        <i class="fas fa-plus me-1"></i>Adicionar
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">`;
    
    // Adicionar cada evento à lista
    eventosDia.forEach(evento => {
        let tipo = evento.extendedProps.tipo || 'outro';
        let titulo = evento.title || 'Evento sem título';
        let classe = '';
        let icone = '';
        let valor = '';
        
        if (tipo === 'transacao') {
            const valorNum = parseFloat(evento.extendedProps.valor || 0);
            const categoria = evento.extendedProps.categoria || 'Sem categoria';
            
            if (valorNum >= 0) {
                classe = 'success';
                icone = 'arrow-up';
                valor = `<span class="badge bg-success-subtle text-success px-2 py-1">R$ ${formatCurrency(valorNum)}</span>`;
            } else {
                classe = 'danger';
                icone = 'arrow-down';
                valor = `<span class="badge bg-danger-subtle text-danger px-2 py-1">R$ ${formatCurrency(Math.abs(valorNum))}</span>`;
            }
            
            // Adicionar ao HTML
            html += `
                <button class="list-group-item list-group-item-action border-start border-${classe} border-3 py-3" 
                        onclick="exibirDetalheEvento(${JSON.stringify(evento).replace(/"/g, '&quot;')})">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-${classe}-subtle me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                    <i class="fas fa-${icone}"></i>
                                </div>
                                <span class="fw-medium">${titulo}</span>
                            </div>
                            <small class="text-muted ms-4 ps-1">${categoria}</small>
                        </div>
                        ${valor}
                    </div>
                </button>
            `;
        } else if (tipo === 'meta') {
            const progresso = parseFloat(evento.extendedProps.progresso || 0);
            
            html += `
                <button class="list-group-item list-group-item-action border-start border-purple border-3 py-3" 
                        onclick="exibirDetalheEvento(${JSON.stringify(evento).replace(/"/g, '&quot;')})">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-purple-subtle me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                    <i class="fas fa-bullseye text-purple"></i>
                                </div>
                                <span class="fw-medium">${titulo}</span>
                            </div>
                            <div class="ms-4 ps-1 mt-1">
                                <div class="progress" style="height: 6px; width: 100px;">
                                    <div class="progress-bar bg-purple" role="progressbar" style="width: ${progresso}%" 
                                         aria-valuenow="${progresso}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <span class="badge bg-purple-subtle text-purple px-2 py-1">${progresso}%</span>
                    </div>
                </button>
            `;
        } else {
            html += `
                <button class="list-group-item list-group-item-action border-start border-primary border-3 py-3"
                        onclick="exibirDetalheEvento(${JSON.stringify(evento).replace(/"/g, '&quot;')})">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary-subtle me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                            <i class="fas fa-calendar-day text-primary"></i>
                        </div>
                        <span class="fw-medium">${titulo}</span>
                    </div>
                </button>
            `;
        }
    });
    
    html += `
                </div>
            </div>
        </div>
    `;
    
    eventosDiaEl.innerHTML = html;
}
</script>
<?= $this->endSection() ?>