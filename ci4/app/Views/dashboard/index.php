<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-roxo fw-bold">Dashboard Financeiro</h1>
        <div class="d-flex">
            <select class="form-select form-select-sm me-2 shadow-sm" id="periodo-filtro">
                <option value="mes">Este Mês</option>
                <option value="trimestre">Último Trimestre</option>
                <option value="ano">Este Ano</option>
                <option value="personalizado">Período Personalizado</option>
            </select>
            <div id="filtro-personalizado" class="me-2" style="display:none;">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control form-control-sm date-input" id="data-personalizada" placeholder="Selecionar período">
                </div>
            </div>
            <button class="btn btn-sm btn-roxo shadow-sm" id="btn-atualizar">
                <i class="fas fa-sync-alt me-1"></i> Atualizar
            </button>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px; font-weight: 600;">Receitas</p>
                            <h3 class="mb-0 fw-bold">R$ <span id="total-receitas" class="text-success">0,00</span></h3>
                            <span class="badge bg-success-subtle text-success mt-2"><i class="fas fa-chart-line me-1"></i><span id="receitas-trend">+0%</span> este mês</span>
                        </div>
                        <div class="bg-gradient-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="fas fa-arrow-up text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4 mb-md-0">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px; font-weight: 600;">Despesas</p>
                            <h3 class="mb-0 fw-bold">R$ <span id="total-despesas" class="text-danger">0,00</span></h3>
                            <span class="badge bg-danger-subtle text-danger mt-2"><i class="fas fa-chart-line me-1"></i><span id="despesas-trend">+0%</span> este mês</span>
                        </div>
                        <div class="bg-gradient-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="fas fa-arrow-down text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-uppercase text-muted mb-2" style="font-size: 0.8rem; letter-spacing: 0.5px; font-weight: 600;">Saldo Atual</p>
                            <h3 class="mb-0 fw-bold">R$ <span id="saldo">0,00</span></h3>
                            <span class="badge bg-primary-subtle text-primary mt-2"><i class="fas fa-piggy-bank me-1"></i>Economia do período</span>
                        </div>
                        <div class="bg-gradient-purple rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                            <i class="fas fa-wallet text-white fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm hover-card h-100">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 text-roxo fw-semibold">Evolução Financeira</h5>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-light" data-chart-view="monthly">Mensal</button>
                            <button type="button" class="btn btn-sm btn-light active" data-chart-view="weekly">Semanal</button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div style="height: 280px; position: relative;">
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm hover-card h-100">
                <div class="card-header bg-white border-0 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 text-roxo fw-semibold">Distribuição de Despesas</h5>
                        <button class="btn btn-sm btn-light">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div style="height: 270px; position: relative;">
                        <canvas id="expensePieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm hover-card">
                <div class="card-header bg-white border-0 p-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-roxo fw-semibold">Últimas Transações</h5>
                    <a href="<?= base_url('transacoes') ?>" class="btn btn-sm btn-roxo">
                        <i class="fas fa-list me-1"></i> Ver Todas
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Data</th>
                                    <th class="px-4 py-3">Descrição</th>
                                    <th class="px-4 py-3">Categoria</th>
                                    <th class="px-4 py-3 text-end">Valor</th>
                                </tr>
                            </thead>
                            <tbody id="latest-transactions">
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <div class="spinner-border spinner-border-sm text-roxo" role="status"></div>
                                        <span class="ms-2">Carregando transações...</span>
                                        <button id="btn-reload-transactions" class="btn btn-sm btn-roxo ms-3">
                                            <i class="fas fa-sync-alt me-1"></i> Recarregar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- CountUp.js (adicionado antes do script principal) -->
<script src="https://cdn.jsdelivr.net/npm/countup.js@2.0.8/dist/countUp.umd.js"></script>

<script>
    $(document).ready(function() {
        // Adicionar classe personalizada para melhorar a aparência do título do mês
        function customizeCalendarTitle(instance) {
            if (instance && instance.calendarContainer) {
                // Adicionar classe específica para nosso estilo
                instance.calendarContainer.classList.add('custom-calendar');
                
                // Garantir que o mês fique visível e centralizado
                const monthElement = instance.calendarContainer.querySelector('.flatpickr-current-month');
                if (monthElement) {
                    monthElement.style.display = 'flex';
                    monthElement.style.justifyContent = 'center';
                    monthElement.style.width = '100%';
                    
                    // Centralizar o texto
                    const monthText = monthElement.querySelector('.cur-month');
                    if (monthText) {
                        monthText.style.textAlign = 'center';
                        monthText.style.width = '100%';
                    }
                }
            }
        }

        // Função para inicializar o flatpickr de forma padronizada
        function initFlatpickr(selector, options = {}) {
            // Verificar se o seletor existe antes de inicializar
            if (!selector) {
                console.warn("Tentativa de inicializar Flatpickr com seletor inválido", selector);
                return null;
            }
            
            // Se for uma string (seletor), verificar se existe algum elemento correspondente
            if (typeof selector === 'string') {
                const elements = document.querySelectorAll(selector);
                if (elements.length === 0) {
                    console.warn("Nenhum elemento encontrado para o seletor Flatpickr:", selector);
                    return null;
                }
            }
            
            // Configuração básica
            const config = {
                dateFormat: "d/m/Y",
                locale: "pt",
                disableMobile: true,
                allowInput: true,
                static: false,
                prevArrow: '<i class="fas fa-chevron-left"></i>',
                nextArrow: '<i class="fas fa-chevron-right"></i>',
                
                // Função para abrir o flatpickr em modo modal
                onOpen: function(selectedDates, dateStr, instance) {
                    // Adicionar classe modal ao calendario
                    instance.calendarContainer.classList.add('flatpickr-modal');
                    
                    // Criar overlay
                    const overlay = document.createElement('div');
                    overlay.className = 'flatpickr-overlay';
                    overlay.id = 'flatpickr-overlay-' + Math.random().toString(36).substring(2, 9);
                    document.body.appendChild(overlay);
                    
                    // Associar o overlay a esta instância
                    instance._overlay = overlay;
                    
                    // Adicionar botão de fechar
                    if (!instance.calendarContainer.querySelector('.flatpickr-close-button')) {
                        const closeButton = document.createElement('button');
                        closeButton.className = 'flatpickr-close-button';
                        closeButton.innerHTML = '<i class="fas fa-times"></i>';
                        closeButton.addEventListener('click', function() {
                            instance.close();
                        });
                        instance.calendarContainer.appendChild(closeButton);
                    }
                    
                    // Adicionar evento de clique ao overlay para fechar o calendário
                    overlay.addEventListener('click', function() {
                        instance.close();
                    });
                    
                    // Personalizar o título do mês
                    customizeCalendarTitle(instance);
                },
                
                // Função para limpar o overlay ao fechar
                onClose: function(selectedDates, dateStr, instance) {
                    // Remover o overlay
                    if (instance._overlay) {
                        instance._overlay.remove();
                        delete instance._overlay;
                    }
                },
                
                // Mesclar com opções adicionais
                ...options
            };
            
            // Inicializar o flatpickr
            try {
                return flatpickr(selector, config);
            } catch (error) {
                console.error("Erro ao inicializar Flatpickr:", error, selector);
                return null;
            }
        }

        // Tentar inicializar o datepicker de período personalizado
        const dataPersonalizadaEl = document.getElementById('data-personalizada');
        if (dataPersonalizadaEl) {
            initFlatpickr(dataPersonalizadaEl, { mode: "range" });
        }
        
        // Mostrar/ocultar campo de período personalizado
        $('#periodo-filtro').change(function() {
            if ($(this).val() === 'personalizado') {
                $('#filtro-personalizado').show();
            } else {
                $('#filtro-personalizado').hide();
            }
        });
        
        // Carregar dados iniciais
        loadDashboardData();
        
        // Handler para botão atualizar
        $('#btn-atualizar').click(function() {
            loadDashboardData();
        });
        
        // Configurar atualização periódica
        setInterval(function() {
            loadDashboardData();
        }, 60000); // Atualiza a cada minuto
        
        // Alternar visualização de gráficos
        $('.btn-group button').on('click', function() {
            $('.btn-group button').removeClass('active');
            $(this).addClass('active');
            const view = $(this).data('chart-view');
            updateChartView(view);
        });
        
        // Adicionar evento para o botão de recarregar transações
        $(document).on('click', '#btn-reload-transactions', function(e) {
            e.preventDefault();
            loadLatestTransactions();
        });
        
        // Filtro de período
        $('#periodo-filtro').on('change', function() {
            loadDashboardData();
        });
    });
    
    // Função para carregar dados do dashboard
    function loadDashboardData() {
        try {
            // Mostrar loading
            showLoading();
            
            // Obter o período selecionado
            const periodo = $('#periodo-filtro').val() || 'mes';
            
            // Parámetros para as chamadas AJAX
            const params = {
                periodo: periodo
            };
            
            // Se for período personalizado, adicionar datas
            if (periodo === 'personalizado') {
                const dataRange = $('#data-personalizada').val();
                if (dataRange) {
                    // Dividir o range em datas de início e fim
                    const dates = dataRange.split(' a ');
                    if (dates.length === 2) {
                        // Converter de DD/MM/YYYY para YYYY-MM-DD
                        const formatDate = (dateStr) => {
                            const parts = dateStr.split('/');
                            return `${parts[2]}-${parts[1]}-${parts[0]}`;
                        };
                        
                        params.data_inicio = formatDate(dates[0]);
                        params.data_fim = formatDate(dates[1]);
                    }
                }
            }
            
            console.log("Carregando dashboard com parâmetros:", params);
            
            // Realizar as chamadas AJAX
            Promise.all([
                loadFinancialSummary(params),
                loadLatestTransactions(params)
            ]).then(() => {
                // Inicializar gráficos após carregar os dados básicos
                loadFinancialChart();
                loadExpensePieChart();
                return Promise.resolve();
            }).catch(err => {
                console.error("Erro ao carregar dashboard:", err);
                $('#latest-transactions').html(`
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-exclamation-circle text-danger fa-2x mb-3"></i>
                                <p class="mb-1">Erro ao carregar dados do dashboard</p>
                                <button type="button" class="btn btn-sm btn-roxo mt-2" onclick="loadDashboardData()">
                                    <i class="fas fa-sync-alt me-1"></i> Tentar novamente
                                </button>
                            </div>
                        </td>
                    </tr>
                `);
            }).finally(() => {
                hideLoading();
            });
        } catch (error) {
            console.error("Erro geral ao carregar dashboard:", error);
            hideLoading();
        }
    }
    
    // Funções auxiliares
    function showLoading() {
        if (!$('#dashboardLoading').length) {
            $('body').append(`
                <div id="dashboardLoading" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
                    background: rgba(255,255,255,0.7); z-index: 9999; display: flex; align-items: center; 
                    justify-content: center;">
                    <div class="spinner-border text-roxo" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
            `);
        } else {
            $('#dashboardLoading').show();
        }
    }
    
    function hideLoading() {
        $('#dashboardLoading').hide();
    }
    
    function loadFinancialSummary(params) {
        return $.ajax({
            url: '<?= base_url('api/transacoes/resumo') ?>',
            type: 'GET',
            data: params,
            success: function(response) {
                if(response.status === 'success') {
                    // Animação suave dos valores (com verificação de existência do CountUp)
                    if (typeof CountUp !== 'undefined') {
                        animateNumber('#total-receitas', response.data.receitas || 0);
                        animateNumber('#total-despesas', response.data.despesas || 0);
                        animateNumber('#saldo', response.data.saldo || 0);
                    } else {
                        // Fallback se CountUp não estiver disponível
                        $('#total-receitas').text(formatCurrency(response.data.receitas || 0));
                        $('#total-despesas').text(formatCurrency(response.data.despesas || 0));
                        $('#saldo').text(formatCurrency(response.data.saldo || 0));
                    }
                    
                    // Ajustar a cor do saldo conforme o valor
                    if((response.data.saldo || 0) < 0) {
                        $('#saldo').removeClass('text-success').addClass('text-danger');
                    } else {
                        $('#saldo').removeClass('text-danger').addClass('text-success');
                    }
                    
                    // Atualizar tendências (dados de exemplo - substituir com dados reais da API)
                    $('#receitas-trend').text('+12%');
                    $('#despesas-trend').text('+8%');
                } else {
                    console.error('Erro ao carregar resumo:', response);
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
            }
        });
    }
    
    function animateNumber(elementId, value) {
        try {
            const el = document.querySelector(elementId);
            if (!el) return;
            
            const currentValue = parseFloat(el.innerText.replace(/[^\d,-]/g, '').replace(',', '.')) || 0;
            
            const countUp = new CountUp(el, value, {
                startVal: currentValue,
                duration: 1,
                useEasing: true,
                useGrouping: true,
                separator: '.',
                decimal: ',',
            });
            
            if (!countUp.error) {
                countUp.start();
            } else {
                console.error('Erro no CountUp:', countUp.error);
                el.textContent = formatCurrency(value);
            }
        } catch (e) {
            console.error('Erro ao animar número:', e);
            document.querySelector(elementId).textContent = formatCurrency(value);
        }
    }
    
    function loadLatestTransactions(params = {}) {
        console.log('Iniciando carregamento de transações com params:', params);
        
        // Exibir loading na tabela
        $('#latest-transactions').html(`
            <tr>
                <td colspan="4" class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-roxo" role="status"></div>
                    <span class="ms-2">Carregando transações recentes...</span>
                </td>
            </tr>
        `);
        
        // Garantir que sempre carregue as últimas 10 transações
        const requestParams = {
            ...params,
            limit: 10  // Solicitar 10 transações mais recentes
        };
        
        console.log('Params para API de transações:', requestParams);
        
        return $.ajax({
            url: '<?= base_url('api/transacoes') ?>',
            type: 'GET',
            data: requestParams,
            timeout: 15000, // 15 segundos de timeout
            success: function(response) {
                console.log('Resposta da API de transações:', response);
                if(response.status === 'success') {
                    let html = '';
                    
                    if(!response.data || response.data.length === 0) {
                        html = '<tr><td colspan="4" class="text-center py-4">Nenhuma transação encontrada</td></tr>';
                    } else {
                        response.data.forEach(function(transaction) {
                            // Determinar a classe CSS com base no tipo de categoria
                            let valueClass = transaction.categoria_tipo === 'receita' ? 'text-success' : 'text-danger';
                            let formattedValue = transaction.categoria_tipo === 'receita' ? 
                                                '+' + formatCurrency(transaction.valor) : 
                                                '-' + formatCurrency(Math.abs(transaction.valor));
                            
                            // Ícone da categoria
                            let categoryIcon = transaction.categoria_tipo === 'receita' ? 
                                              'fa-arrow-up' : 'fa-arrow-down';
                            
                            html += `
                                <tr>
                                    <td class="px-4 py-3">${formatDate(transaction.data)}</td>
                                    <td class="px-4 py-3">${transaction.descricao}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge rounded-pill ${transaction.categoria_tipo === 'receita' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'}">
                                            <i class="fas ${categoryIcon} me-1"></i>${transaction.categoria_nome || 'Categoria'}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-end fw-bold ${valueClass}">${formattedValue}</td>
                                </tr>
                            `;
                        });
                    }
                    
                    console.log('Atualizando tabela com HTML:', html.substring(0, 100) + '...');
                    $('#latest-transactions').html(html);
                } else {
                    console.error('Erro na resposta da API:', response);
                    $('#latest-transactions').html('<tr><td colspan="4" class="text-center py-4">Erro ao carregar transações recentes.</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error, xhr.responseText);
                $('#latest-transactions').html(`
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-exclamation-circle text-danger fa-2x mb-3"></i>
                                <p class="mb-1">Erro ao carregar transações recentes</p>
                                <button type="button" class="btn btn-sm btn-roxo mt-2" onclick="loadLatestTransactions()">
                                    <i class="fas fa-sync-alt me-1"></i> Tentar novamente
                                </button>
                            </div>
                        </td>
                    </tr>
                `);
            }
        });
    }
    
    function loadFinancialChart() {
        try {
            // Verificar se Chart.js está disponível
            if (typeof Chart === 'undefined') {
                console.error('Chart.js não está disponível');
                return;
            }
            
            // Simulação de dados para o gráfico de linha
            const chartElement = document.getElementById('financialChart');
            if (!chartElement) {
                console.warn('Elemento do gráfico financeiro não encontrado');
                return;
            }
            
            const ctx = chartElement.getContext('2d');
            if (!ctx) {
                console.warn('Contexto 2D do canvas não disponível');
                return;
            }
            
            // Gradiente para área de receitas
            const receitasGradient = ctx.createLinearGradient(0, 0, 0, 250);
            receitasGradient.addColorStop(0, 'rgba(40, 167, 69, 0.3)');
            receitasGradient.addColorStop(1, 'rgba(40, 167, 69, 0.02)');
            
            // Gradiente para área de despesas
            const despesasGradient = ctx.createLinearGradient(0, 0, 0, 250);
            despesasGradient.addColorStop(0, 'rgba(220, 53, 69, 0.3)');
            despesasGradient.addColorStop(1, 'rgba(220, 53, 69, 0.02)');
            
            // Dados de exemplo (substituir por dados reais da API)
            const labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];
            const receitas = [1200, 1900, 2100, 1800, 2400, 2800];
            const despesas = [900, 1200, 1800, 1600, 2000, 2200];
            
            // Destruir gráfico existente se houver
            if (window.financialChart && typeof window.financialChart.destroy === 'function') {
                window.financialChart.destroy();
            }
            
            window.financialChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Receitas',
                            data: receitas,
                            borderColor: '#28a745',
                            backgroundColor: receitasGradient,
                            borderWidth: 2,
                            pointBackgroundColor: '#28a745',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Despesas',
                            data: despesas,
                            borderColor: '#dc3545',
                            backgroundColor: despesasGradient,
                            borderWidth: 2,
                            pointBackgroundColor: '#dc3545',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 10,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#333',
                            bodyColor: '#666',
                            bodyFont: {
                                size: 13
                            },
                            borderWidth: 1,
                            borderColor: '#e0e0e0',
                            displayColors: true,
                            padding: 12,
                            boxPadding: 8,
                            usePointStyle: true,
                            callbacks: {
                                labelPointStyle: function(context) {
                                    return {
                                        pointStyle: 'circle',
                                        rotation: 0
                                    };
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                borderDash: [3, 3],
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'R$ ' + value.toLocaleString('pt-BR');
                                }
                            }
                        }
                    }
                }
            });
        } catch (e) {
            console.error('Erro ao criar gráfico financeiro:', e);
        }
    }
    
    function loadExpensePieChart() {
        try {
            // Verificar se Chart.js está disponível
            if (typeof Chart === 'undefined') {
                console.error('Chart.js não está disponível');
                return;
            }
            
            const chartElement = document.getElementById('expensePieChart');
            if (!chartElement) {
                console.warn('Elemento do gráfico de despesas não encontrado');
                return;
            }
            
            const ctx = chartElement.getContext('2d');
            if (!ctx) {
                console.warn('Contexto 2D do canvas não disponível');
                return;
            }
            
            // Dados de exemplo (substituir por dados reais da API)
            const categorias = ['Alimentação', 'Moradia', 'Transporte', 'Saúde', 'Outros'];
            const valores = [30, 25, 15, 10, 20];
            const cores = [
                '#693976', // Roxo primário
                '#8a5b9a', // Roxo claro
                '#4a2852', // Roxo escuro
                '#a682b2', // Roxo muito claro
                '#2e1934'  // Roxo muito escuro
            ];
            
            // Destruir gráfico existente se houver
            if (window.expensePieChart && typeof window.expensePieChart.destroy === 'function') {
                window.expensePieChart.destroy();
            }
            
            window.expensePieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: categorias,
                    datasets: [{
                        data: valores,
                        backgroundColor: cores,
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                boxWidth: 10,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#333',
                            bodyColor: '#666',
                            bodyFont: {
                                size: 13
                            },
                            borderWidth: 1,
                            borderColor: '#e0e0e0',
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${context.label}: ${percentage}% (R$ ${value.toLocaleString('pt-BR')})`;
                                }
                            }
                        }
                    }
                }
            });
        } catch (e) {
            console.error('Erro ao criar gráfico de despesas:', e);
        }
    }
    
    function updateChartView(view) {
        try {
            // Verificar se o gráfico financeiro existe
            if (!window.financialChart || typeof window.financialChart.update !== 'function') {
                console.warn('Gráfico financeiro não está disponível para atualização');
                // Tentar recriar o gráfico
                loadFinancialChart();
                return;
            }
            
            // Dados de exemplo para diferentes visualizações (substituir com dados reais da API)
            if (view === 'weekly') {
                // Dados semanais
                const labels = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'];
                const receitas = [300, 450, 320, 280, 560, 720, 390];
                const despesas = [220, 380, 275, 350, 440, 580, 320];
                
                updateChartData(window.financialChart, labels, receitas, despesas);
            } else {
                // Dados mensais
                const labels = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];
                const receitas = [1200, 1900, 2100, 1800, 2400, 2800];
                const despesas = [900, 1200, 1800, 1600, 2000, 2200];
                
                updateChartData(window.financialChart, labels, receitas, despesas);
            }
        } catch (e) {
            console.error('Erro ao atualizar visualização do gráfico:', e);
        }
    }
    
    function updateChartData(chart, labels, receitas, despesas) {
        if (!chart || typeof chart.update !== 'function') {
            console.warn('Gráfico inválido para atualização');
            return;
        }
        
        try {
            chart.data.labels = labels;
            chart.data.datasets[0].data = receitas;
            chart.data.datasets[1].data = despesas;
            chart.update();
        } catch (error) {
            console.error('Erro ao atualizar dados do gráfico:', error);
        }
    }
    
    function formatCurrency(value) {
        return parseFloat(value || 0).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
    
    function formatDate(dateString) {
        // Corrigindo problema de fuso horário que estava causando diferença de 1 dia
        // Usar split e construir a data manualmente para evitar conversão de fuso horário
        const parts = dateString.split('-');
        if (parts.length === 3) {
            // Formato ano-mes-dia vindo do banco (2025-04-05)
            return `${parts[2]}/${parts[1]}/${parts[0]}`;
        } else {
            // Fallback para o método anterior caso o formato não seja o esperado
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        }
    }

    function loadExpensesByCategory(params = {}) {
        return $.ajax({
            url: '<?= base_url('api/transacoes/despesas-por-categoria') ?>',
            type: 'GET',
            data: params,
            success: function(response) {
                if(response.status === 'success') {
                    renderExpensesChart(response.data);
                }
            }
        });
    }

    function loadIncomesByCategory(params = {}) {
        return $.ajax({
            url: '<?= base_url('api/transacoes/receitas-por-categoria') ?>',
            type: 'GET',
            data: params,
            success: function(response) {
                if(response.status === 'success') {
                    renderIncomesChart(response.data);
                }
            }
        });
    }

    function loadMonthlyTrend(params = {}) {
        return $.ajax({
            url: '<?= base_url('api/transacoes/tendencia-mensal') ?>',
            type: 'GET',
            data: params,
            success: function(response) {
                if(response.status === 'success') {
                    renderTrendChart(response.data);
                }
            }
        });
    }
</script>
<?= $this->endSection() ?> 