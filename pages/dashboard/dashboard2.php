<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"><!-- Primary Meta Tags -->
  <title>Volt Premium Bootstrap Dashboard - App Analysis</title>
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
  <meta name="title" content="Volt Premium Bootstrap Dashboard - App Analysis">
  <meta name="author" content="Themesberg">
  <meta name="description"
    content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS.">
  <meta name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, themesberg, themesberg dashboard, themesberg admin dashboard">
  <link rel="canonical" href="https://themesberg.com/product/admin-dashboard/volt-premium-bootstrap-5-dashboard">
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://demo.themesberg.com/volt-pro">
  <meta property="og:title" content="Volt Premium Bootstrap Dashboard - App Analysis">
  <meta property="og:description"
    content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS.">
  <meta property="og:image"
    content="../../../../themesberg.s3.us-east-2.amazonaws.com/public/products/volt-pro-bootstrap-5-dashboard/volt-pro-preview.jpg">
  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="https://demo.themesberg.com/volt-pro">
  <meta property="twitter:title" content="Volt Premium Bootstrap Dashboard - App Analysis">
  <meta property="twitter:description"
    content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS.">
  <meta property="twitter:image"
    content="../../../../themesberg.s3.us-east-2.amazonaws.com/public/products/volt-pro-bootstrap-5-dashboard/volt-pro-preview.jpg">
  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="120x120" href="../../assets/img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../../assets/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../../assets/img/favicon/favicon-16x16.png">

  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff"><!-- Sweet Alert -->
  <link type="text/css" href="../../vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet"><!-- Notyf -->
  <link type="text/css" href="../../vendor/notyf/notyf.min.css" rel="stylesheet"><!-- Full Calendar  -->
  <link type="text/css" href="../../vendor/fullcalendar/main.min.css" rel="stylesheet"><!-- Apex Charts -->
  <link type="text/css" href="../../vendor/apexcharts/dist/apexcharts.css" rel="stylesheet"><!-- Dropzone -->
  <link type="text/css" href="../../vendor/dropzone/dist/min/dropzone.min.css" rel="stylesheet"><!-- Choices  -->
  <!-- Leaflet JS -->
  <link type="text/css" href="../../vendor/leaflet/dist/leaflet.css" rel="stylesheet"><!-- Volt CSS -->
  <link type="text/css" href="../../css/volt.css" rel="stylesheet">
  <!-- NOTICE: You can use the _analytics.html partial to include production code specific code & trackers --><!-- Global site tag (gtag.js) - Google Analytics -->


  <style>
  /* Adiciona espaçamento entre os cards */
  .row > .col-md-3, .row > .col-md-4, .row > .col-md-6, .row > .col-12 {
    margin-bottom: 15px; /* Espaçamento inferior entre os cards */
  }

  /* Remove o espaçamento para dispositivos maiores */
  @media (min-width: 768px) {
    .row > .col-md-3, .row > .col-md-4, .row > .col-md-6, .row > .col-12 {
      margin-bottom: 0;
    }
  }
</style>

</head>


<body>
  <main class="content">
    <?php include("menu.php") ?>

    <!-- Conteúdo Principal -->
    <div class="container-fluid py-4">

      <!-- Gráfico de Vendas -->
      <div class="row mb-4">
        <div class="col-12">
          <div class="card border-0 shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h2 class="h5 mb-0">Gráfico de Vendas</h2>
              <div>
                <button class="btn btn-sm btn-gray-800 me-2" id="toggle-month">Mês</button>
                <button class="btn btn-sm btn-outline-gray-800" id="toggle-week">Semana</button>
              </div>
            </div>
            <div class="card-body">
              <canvas id="salesChart" style="height: 300px;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Linha de Cards Resumo -->
      <div class="row">
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Faturamento Mensal</h5>
              <h3 class="fw-bold">R$ 25.000</h3>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Despesas Totais</h5>
              <h3 class="fw-bold">R$ 15.000</h3>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Contratos Ativos</h5>
              <h3 class="fw-bold">8</h3>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Transações Pendentes</h5>
              <h3 class="fw-bold">12</h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Linha de Gráficos Adicionais -->
      <div class="row mt-4">
        <div class="col-md-4">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Receitas e Despesas</h5>
              <canvas id="chartReceitasDespesas"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Categorias</h5>
              <canvas id="chartCategorias"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Inadimplência</h5>
              <canvas id="chartInadimplencia"></canvas>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- JS do Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Gráfico de Vendas
    const ctxSales = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctxSales, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
          label: 'Vendas',
          data: [500, 700, 1000, 1200, 1400, 2000, 2500, 3000, 3500, 3700, 4000, 4500],
          backgroundColor: 'rgba(105, 57, 118, 0.2)',
          borderColor: '#693976',
          borderWidth: 2,
          fill: true
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          },
        },
        scales: {
          x: {
            grid: {
              color: '#ddd'
            }
          },
          y: {
            beginAtZero: true,
            grid: {
              color: '#ddd'
            }
          }
        }
      }
    });

    // Gráfico de Receitas e Despesas
    const ctxReceitasDespesas = document.getElementById('chartReceitasDespesas').getContext('2d');
    new Chart(ctxReceitasDespesas, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai'],
        datasets: [{
            label: 'Receitas',
            data: [12000, 15000, 18000, 20000, 25000],
            backgroundColor: 'rgba(105, 57, 118, 0.8)'
          },
          {
            label: 'Despesas',
            data: [8000, 10000, 12000, 14000, 15000],
            backgroundColor: 'rgba(161, 131, 180, 0.8)'
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top'
          },
        },
        scales: {
          x: {
            grid: {
              color: '#ddd'
            }
          },
          y: {
            beginAtZero: true,
            grid: {
              color: '#ddd'
            }
          }
        }
      }
    });

    // Gráfico de Categorias
    const ctxCategorias = document.getElementById('chartCategorias').getContext('2d');
    new Chart(ctxCategorias, {
      type: 'doughnut',
      data: {
        labels: ['Aluguel', 'Materiais', 'Salários', 'Outros'],
        datasets: [{
          data: [5000, 3000, 7000, 2000],
          backgroundColor: [
            'rgba(105, 57, 118, 0.8)',
            'rgba(161, 131, 180, 0.8)',
            'rgba(90, 45, 102, 0.8)',
            'rgba(193, 170, 217, 0.8)'
          ]
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'right'
          },
        }
      }
    });

    // Gráfico de Inadimplência
    const ctxInadimplencia = document.getElementById('chartInadimplencia').getContext('2d');
    new Chart(ctxInadimplencia, {
      type: 'pie',
      data: {
        labels: ['Pagos', 'Atrasados', 'Não Pagos'],
        datasets: [{
          data: [70, 20, 10],
          backgroundColor: [
            'rgba(105, 57, 118, 0.8)',
            'rgba(161, 131, 180, 0.8)',
            'rgba(193, 170, 217, 0.8)'
          ]
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          },
        }
      }
    });
  </script>
</body>




<script src="../../vendor/%40popperjs/core/dist/umd/popper.min.js"></script>
<script src="../../vendor/bootstrap/dist/js/bootstrap.min.js"></script><!-- Vendor JS -->
<script src="../../vendor/onscreen/dist/on-screen.umd.min.js"></script><!-- Slider -->
<script src="../../vendor/nouislider/distribute/nouislider.min.js"></script><!-- Smooth scroll -->
<script src="../../vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script><!-- Count up -->
<script src="../../vendor/countup.js/dist/countUp.umd.js"></script><!-- Apex Charts -->
<script src="../../vendor/apexcharts/dist/apexcharts.min.js"></script><!-- Datepicker -->
<script src="../../vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script><!-- DataTables -->
<script src="../../vendor/simple-datatables/dist/umd/simple-datatables.js"></script><!-- Sweet Alerts 2 -->
<script src="../../vendor/sweetalert2/dist/sweetalert2.min.js"></script><!-- Moment JS -->
<!-- Vanilla JS Datepicker -->
<script src="../../vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script><!-- Full Calendar -->
<script src="../../vendor/fullcalendar/main.min.js"></script><!-- Dropzone -->
<script src="../../vendor/dropzone/dist/min/dropzone.min.js"></script><!-- Choices.js -->
<script src="../../vendor/notyf/notyf.min.js"></script><!-- Mapbox & Leaflet.js -->
<script src="../../vendor/leaflet/dist/leaflet.js"></script><!-- SVG Map -->
<script src="../../vendor/svgmap/dist/svgMap.min.js"></script><!-- Simplebar -->
<script src="../../vendor/simplebar/dist/simplebar.min.js"></script><!-- Sortable Js -->
<script src="../../vendor/sortablejs/Sortable.min.js"></script><!-- Github buttons -->
<script src="../../assets/js/volt.js"></script>

</body>
<!-- Mirrored from demo.themesberg.com/volt-pro/pages/dashboard/app-analysis.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 21 Dec 2024 19:42:48 GMT -->

</html>