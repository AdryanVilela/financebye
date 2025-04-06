<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"><!-- Primary Meta Tags -->
  <title>Finance Bye</title>
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
  <meta name="title" content="Volt - Premium Bootstrap 5 Dashboard">
  <meta name="author" content="Themesberg">
  <meta name="description"
    content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS.">
  <meta name="keywords"
    content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, themesberg, themesberg dashboard, themesberg admin dashboard">
  <link rel="canonical" href="https://themesberg.com/product/admin-dashboard/volt-premium-bootstrap-5-dashboard">
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://demo.themesberg.com/volt-pro">
  <meta property="og:title" content="Volt - Premium Bootstrap 5 Dashboard">
  <meta property="og:description"
    content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS.">
  <meta property="og:image"
    content="../../assets/img/logo/financebye.ico">
  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="https://demo.themesberg.com/volt-pro">
  <meta property="twitter:title" content="Volt - Premium Bootstrap 5 Dashboard">
  <meta property="twitter:description"
    content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS.">
  <meta property="twitter:image"
    content="../../assets/img/logo/financebye.ico">
  <!-- Favicon -->
  <link rel="apple-touch-icon" sizes="120x120" href="../../assets/img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../../assets/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../../assets/img/favicon/favicon-16x16.png">
  <link rel="manifest" href="https://demo.themesberg.com/volt-pro/assets/img/favicon/site.webmanifest">
  <link rel="mask-icon" href="https://demo.themesberg.com/volt-pro/assets/img/favicon/safari-pinned-tab.svg"
    color="#ffffff">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff"><!-- Sweet Alert -->
  <link type="text/css" href="../../vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet"><!-- Notyf -->
  <link type="text/css" href="../../vendor/notyf/notyf.min.css" rel="stylesheet"><!-- Full Calendar  -->
  <link type="text/css" href="../../vendor/fullcalendar/main.min.css" rel="stylesheet"><!-- Apex Charts -->
  <link type="text/css" href="../../vendor/apexcharts/dist/apexcharts.css" rel="stylesheet"><!-- Dropzone -->
  <link type="text/css" href="../../vendor/dropzone/dist/min/dropzone.min.css" rel="stylesheet"><!-- Choices  -->
  <link type="text/css" href="../../vendor/choices.js/public/assets/styles/choices.min.css" rel="stylesheet">
  <!-- Leaflet JS -->
  <link type="text/css" href="../../vendor/leaflet/dist/leaflet.css" rel="stylesheet"><!-- Volt CSS -->
  <link type="text/css" href="../../css/volt.css" rel="stylesheet">
  <!-- NOTICE: You can use the _analytics.html partial to include production code specific code & trackers --><!-- Global site tag (gtag.js) - Google Analytics -->

</head>

<body>
  <main class="content">
    <?php include("menu.php") ?>

    <div class="container-fluid py-4">
      <!-- Resumo Geral -->
      <div class="row">
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Total de Funcionários</h5>
              <h3 class="fw-bold">45</h3>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Horas Trabalhadas</h5>
              <h3 class="fw-bold">1.200h</h3>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Tarefas Concluídas</h5>
              <h3 class="fw-bold">125</h3>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Ausências Registradas</h5>
              <h3 class="fw-bold">5</h3>
            </div>
          </div>
        </div>
      </div>

      <!-- Gráficos -->
      <div class="row mt-4">
        <div class="col-md-6 mb-3">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Produtividade Semanal</h5>
              <canvas id="chart-prod-semanal"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6 mb-3">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Distribuição de Equipes</h5>
              <canvas id="chart-equipes"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-md-6 mb-3">
          <div class="card border-0 shadow">
            <div class="card-body">
              <h5 class="card-title">Tarefas por Status</h5>
              <canvas id="chart-tarefas-status"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- JS do Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Produtividade Semanal
    const ctx1 = document.getElementById('chart-prod-semanal').getContext('2d');
    new Chart(ctx1, {
      type: 'line',
      data: {
        labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex'],
        datasets: [{
          label: 'Horas Trabalhadas',
          data: [8, 7.5, 8.5, 8, 7],
          borderColor: 'rgba(105, 57, 118, 1)',
          backgroundColor: 'rgba(105, 57, 118, 0.2)',
          tension: 0.4,
          fill: true
        }]
      }
    });

    // Distribuição de Equipes
    const ctx3 = document.getElementById('chart-equipes').getContext('2d');
    new Chart(ctx3, {
      type: 'bar',
      data: {
        labels: ['TI', 'Financeiro', 'Marketing', 'RH'],
        datasets: [{
          label: 'Número de Funcionários',
          data: [15, 10, 12, 8],
          backgroundColor: 'rgba(105, 57, 118, 0.8)'
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    // Tarefas por Status
    const ctx2 = document.getElementById('chart-tarefas-status').getContext('2d');
    new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: ['Concluídas', 'Pendentes', 'Em Andamento'],
        datasets: [{
          data: [50, 20, 30],
          backgroundColor: [
            'rgba(105, 57, 118, 0.8)',
            'rgba(161, 131, 180, 0.8)',
            'rgba(193, 170, 217, 0.8)'
          ]
        }]
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

<!-- Mirrored from demo.themesberg.com/volt-pro/pages/dashboard/dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 21 Dec 2024 19:42:48 GMT -->

</html>