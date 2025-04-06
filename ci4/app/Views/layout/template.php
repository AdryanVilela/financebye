<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinanceBye - <?= $title ?? 'Sistema Financeiro' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Flatpickr - Datepicker moderno -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_purple.css">
    
    <style>
        :root {
            --roxo-primary: #693976;
            --roxo-light: #8a5b9a;
            --roxo-dark: #4a2852;
            --cinza-bg: #f8f9fa;
            --text-color: #444;
            --card-shadow: 0 8px 24px rgba(149, 157, 165, 0.1);
        }
        
        /* Estilos simplificados para o Flatpickr */
        .flatpickr-calendar {
            background: white !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            border: none !important;
            width: 325px !important;
            padding: 15px !important;
            font-family: 'Poppins', sans-serif !important;
        }
        
        /* Estilo modal */
        .flatpickr-modal {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
            z-index: 999999 !important;
        }
        
        /* Overlay */
        .flatpickr-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: rgba(0, 0, 0, 0.6) !important;
            z-index: 99998 !important;
            -webkit-backdrop-filter: blur(2px) !important;
            backdrop-filter: blur(2px) !important;
            opacity: 1 !important;
            cursor: pointer !important;
        }
        
        /* Cabeçalho com o nome do mês - centralizado */
        .flatpickr-calendar .flatpickr-month {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            position: relative !important;
            padding: 10px 0 !important;
            width: 100% !important;
            overflow: hidden !important;
            height: 50px !important;
        }
        
        /* Título do mês centralizado */
        .flatpickr-current-month {
            width: auto !important;
            position: absolute !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        /* Elementos dentro da exibição do mês */
        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-current-month span.cur-month,
        .flatpickr-current-month .numInputWrapper {
            text-align: center !important;
            font-size: 1.2rem !important;
            font-weight: 600 !important;
            color: var(--roxo-primary) !important;
            margin: 0 auto !important;
            display: inline-block !important;
        }
        
        /* Botões de navegação */
        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            height: 34px !important;
            width: 34px !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 50% !important;
            background-color: rgba(105, 57, 118, 0.1) !important;
            color: var(--roxo-primary) !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            position: absolute !important;
            z-index: 10 !important;
        }
        
        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            background-color: var(--roxo-light) !important;
        }
        
        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            fill: var(--roxo-primary) !important;
            width: 14px !important;
            height: 14px !important;
        }
        
        .flatpickr-months .flatpickr-prev-month:hover svg,
        .flatpickr-months .flatpickr-next-month:hover svg {
            fill: white !important;
        }
        
        .flatpickr-months .flatpickr-prev-month {
            left: 15px !important;
        }
        
        .flatpickr-months .flatpickr-next-month {
            right: 15px !important;
        }
        
        /* Dias da semana */
        span.flatpickr-weekday {
            color: var(--roxo-primary) !important;
            font-weight: 600 !important;
            font-size: 0.9em !important;
        }
        
        /* Dias */
        .flatpickr-day {
            color: var(--text-color) !important;
            border-radius: 8px !important;
            font-weight: 500 !important;
            border: none !important;
            margin: 3px 2px !important;
        }
        
        .flatpickr-day.selected {
            background-color: var(--roxo-primary) !important;
            color: white !important;
            box-shadow: 0 3px 8px rgba(105, 57, 118, 0.3) !important;
        }
        
        .flatpickr-day.today {
            border: 1px solid var(--roxo-light) !important;
            color: var(--roxo-primary) !important;
        }
        
        .flatpickr-day.inRange {
            background-color: rgba(105, 57, 118, 0.2) !important;
            box-shadow: none !important;
        }
        
        .flatpickr-day.startRange, .flatpickr-day.endRange {
            background-color: var(--roxo-primary) !important;
            color: white !important;
        }
        
        /* Botão de fechar */
        .flatpickr-close-button {
            position: absolute !important;
            top: 10px !important;
            right: 10px !important;
            background: rgba(240, 240, 240, 0.8) !important;
            color: var(--roxo-primary) !important;
            width: 28px !important;
            height: 28px !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            z-index: 100 !important;
            font-size: 14px !important;
            border: none !important;
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            .flatpickr-modal {
                width: 90% !important;
                max-width: 320px !important;
            }
            
            .flatpickr-day {
                height: 36px !important;
                line-height: 36px !important;
                width: 36px !important;
                max-width: 36px !important;
            }
        }
        
        /* Estilos gerais */
        body {
            background-color: var(--cinza-bg);
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            overflow-x: hidden;
        }
        
        /* Novo estilo para o sidebar moderno */
        .sidebar-container {
            position: fixed;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            z-index: 1000;
            pointer-events: none;
        }
        
        .sidebar {
            position: absolute;
            width: 280px;
            height: 100%;
            background: #fff;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.12);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            padding: 0;
            overflow-y: auto;
            pointer-events: auto;
            transform: translateX(-5px);
            opacity: 0.98;
        }
        
        .sidebar.collapsed {
            transform: translateX(-280px);
        }
        
        .sidebar-header {
            background: linear-gradient(135deg, var(--roxo-primary) 0%, var(--roxo-dark) 100%);
            padding: 25px 20px;
            color: white;
            position: relative;
        }
        
        .sidebar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            margin: 0;
            display: flex;
            align-items: center;
        }
        
        .sidebar-brand i {
            font-size: 2rem;
            margin-right: 10px;
        }
        
        .sidebar-subtitle {
            opacity: 0.85;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        
        .sidebar-user {
            display: flex;
            align-items: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 600;
            margin-right: 15px;
        }
        
        .sidebar-userinfo {
            flex: 1;
            overflow: hidden;
        }
        
        .sidebar-username {
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .sidebar-useremail {
            font-size: 0.75rem;
            opacity: 0.75;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .sidebar-menu {
            padding: 15px 0;
        }
        
        .menu-section {
            padding: 0 15px;
            margin-bottom: 10px;
        }
        
        .menu-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 10px;
            padding: 0 10px;
        }
        
        .nav-item {
            margin-bottom: 5px;
        }
        
        .nav-link {
            color: #555;
            padding: 12px 15px;
            border-radius: 10px;
            margin: 0 10px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background-color: rgba(105, 57, 118, 0.04);
            color: var(--roxo-primary);
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, var(--roxo-primary) 0%, var(--roxo-dark) 100%);
            color: white;
            box-shadow: 0 5px 12px rgba(105, 57, 118, 0.3);
            transform: none;
        }
        
        .nav-link i {
            margin-right: 12px;
            font-size: 1.2rem;
            opacity: 0.85;
            width: 24px;
            text-align: center;
        }
        
        .nav-link .badge {
            margin-left: auto;
        }
        
        .main-content {
            width: 100%;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            padding-left: 280px;
        }
        
        .main-content.expanded {
            padding-left: 0;
        }
        
        .top-bar {
            background-color: white;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            padding: 12px 25px;
            display: flex;
            align-items: center;
            border-radius: 15px;
            margin-bottom: 25px;
        }
        
        .menu-toggle {
            background: none;
            border: none;
            color: #888;
            font-size: 1.3rem;
            margin-right: 15px;
            cursor: pointer;
            padding: 5px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .menu-toggle:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--roxo-primary);
        }
        
        .page-title {
            font-weight: 600;
            color: #444;
            margin: 0;
            flex: 1;
        }
        
        .top-bar-actions {
            display: flex;
            align-items: center;
        }
        
        .notification-btn {
            background: none;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #777;
            position: relative;
            margin-right: 8px;
            transition: all 0.3s;
        }
        
        .notification-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--roxo-primary);
        }
        
        .notification-btn .badge {
            position: absolute;
            top: -3px;
            right: -3px;
            background-color: #dc3545;
            color: white;
            border: 2px solid white;
            font-size: 0.65rem;
            padding: 0.15em 0.4em;
        }
        
        .user-dropdown {
            margin-left: 8px;
        }
        
        .user-dropdown-toggle {
            background: none;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--roxo-light) 0%, var(--roxo-primary) 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 3px 10px rgba(105, 57, 118, 0.2);
        }
        
        .dropdown-menu {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 24px rgba(149, 157, 165, 0.2);
            overflow: hidden;
            min-width: 200px;
        }
        
        .dropdown-menu-header {
            background: linear-gradient(135deg, var(--roxo-primary) 0%, var(--roxo-dark) 100%);
            color: white;
            padding: 15px;
        }
        
        .dropdown-menu-user {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 1.1rem;
        }
        
        .dropdown-menu-email {
            font-size: 0.8rem;
            opacity: 0.9;
        }
        
        .dropdown-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        
        .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            color: #888;
        }
        
        .dropdown-item:hover {
            background-color: rgba(105, 57, 118, 0.05);
            color: var(--roxo-primary);
        }
        
        .dropdown-item:hover i {
            color: var(--roxo-primary);
        }
        
        .dropdown-divider {
            margin: 0;
        }
        
        .content-area {
            padding: 25px;
        }
        
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: none;
            margin-bottom: 25px;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 20px 25px;
            font-weight: 600;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #777;
            padding: 15px 25px;
            background-color: var(--cinza-bg);
        }
        
        .table td {
            padding: 15px 25px;
            vertical-align: middle;
        }
        
        .btn-roxo {
            background: linear-gradient(135deg, var(--roxo-primary) 0%, var(--roxo-dark) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(105, 57, 118, 0.2);
        }
        
        .btn-roxo:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(105, 57, 118, 0.3);
            color: white;
        }
        
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-280px);
            }
            
            .sidebar.expanded {
                transform: translateX(-5px);
            }
            
            .main-content {
                padding-left: 0;
            }
        }
        
        @media (max-width: 576px) {
            .content-area {
                padding: 15px;
            }
            
            .top-bar {
                padding: 12px 15px;
                margin-bottom: 15px;
            }
        }
        
        /* Estilos específicos para o calendário personalizado */
        .custom-calendar .flatpickr-month {
            display: flex !important;
            justify-content: center !important;
            position: relative !important;
            padding: 15px 0 !important;
            margin-bottom: 10px !important;
        }
        
        .custom-calendar .flatpickr-current-month {
            display: inline-flex !important;
            justify-content: center !important;
            align-items: center !important;
            width: 100% !important;
            left: 0 !important;
            transform: none !important;
            position: relative !important;
        }
        
        .custom-calendar .cur-month {
            display: inline-block !important;
            width: auto !important;
            text-align: center !important;
            font-size: 1.3rem !important;
            font-weight: 600 !important;
            color: var(--roxo-primary) !important;
            margin: 0 !important;
        }
        
        /* Exibir corretamente o ano */
        .custom-calendar .flatpickr-current-month .numInputWrapper {
            display: inline-block !important;
            margin-left: 5px !important;
        }
        
        .custom-calendar .flatpickr-months .flatpickr-prev-month, 
        .custom-calendar .flatpickr-months .flatpickr-next-month {
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
    </style>
</head>
<body>
    <!-- Sidebar Container -->
    <div class="sidebar-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h5 class="sidebar-brand"><i class="fas fa-chart-pie"></i> FinanceBye</h5>
                <p class="sidebar-subtitle">Sistema de Gestão Financeira</p>
                
                <div class="sidebar-user">
                    <div class="sidebar-avatar">
                        <?= substr(session()->get('usuario')['nome'] ?? 'U', 0, 1) ?>
                    </div>
                    <div class="sidebar-userinfo">
                        <div class="sidebar-username"><?= session()->get('usuario')['nome'] ?? 'Usuário' ?></div>
                        <div class="sidebar-useremail"><?= session()->get('usuario')['email'] ?? 'usuario@exemplo.com' ?></div>
                    </div>
                </div>
            </div>
            
            <div class="sidebar-menu">
                <div class="menu-section">
                    <h6 class="menu-section-title">Principal</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'dashboard' ? 'active' : '' ?>" href="<?= base_url('dashboard') ?>">
                                <i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'transacoes' ? 'active' : '' ?>" href="<?= base_url('transacoes') ?>">
                                <i class="fas fa-exchange-alt"></i>
                                <span>Transações</span>
                                <span class="badge rounded-pill bg-primary">Novo</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'metas' ? 'active' : '' ?>" href="<?= base_url('metas') ?>">
                                <i class="fas fa-bullseye"></i>
                                <span>Metas Financeiras</span>
                                <span class="badge rounded-pill bg-success">Novo</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'calendario' ? 'active' : '' ?>" href="<?= base_url('calendario') ?>">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Calendário Financeiro</span>
                                <span class="badge rounded-pill bg-info">Novo</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="menu-section">
                    <h6 class="menu-section-title">Gerenciamento</h6>
                    <ul class="nav flex-column">
                       
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'clientes' ? 'active' : '' ?>" href="<?= base_url('clientes') ?>">
                                <i class="fas fa-users"></i>
                                <span>Clientes</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'fornecedores' ? 'active' : '' ?>" href="<?= base_url('fornecedores') ?>">
                                <i class="fas fa-building"></i>
                                <span>Fornecedores</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'categorias' ? 'active' : '' ?>" href="<?= base_url('categorias') ?>">
                                <i class="fas fa-tags"></i>
                                <span>Categorias</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'usuarios' ? 'active' : '' ?>" href="<?= base_url('usuarios') ?>">
                                <i class="fas fa-users"></i>
                                <span>Usuários</span>
                            </a>
                        </li>
                        
                    </ul>
                </div>
                
                <div class="menu-section">
                    <h6 class="menu-section-title">Configurações</h6>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= uri_string() == 'perfil' ? 'active' : '' ?>" href="<?= base_url('perfil') ?>">
                                <i class="fas fa-user-circle"></i>
                                <span>Meu Perfil</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('logout') ?>">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Sair</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Top Bar -->
        <div class="content-area">
            <div class="top-bar">
                <button type="button" id="menu-toggle" class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <h4 class="page-title"><?= $title ?? 'Dashboard' ?></h4>
                
                <div class="top-bar-actions">
                    <button type="button" class="notification-btn">
                        <i class="fas fa-bell"></i>
                        <span class="badge rounded-pill">3</span>
                    </button>
                    
                    <div class="dropdown user-dropdown">
                        <button class="user-dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <div class="avatar">
                                <?= substr(session()->get('usuario')['nome'] ?? 'U', 0, 1) ?>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <div class="dropdown-menu-header">
                                    <div class="dropdown-menu-user"><?= session()->get('usuario')['nome'] ?? 'Usuário' ?></div>
                                    <div class="dropdown-menu-email"><?= session()->get('usuario')['email'] ?? 'usuario@exemplo.com' ?></div>
                                </div>
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url('perfil') ?>"><i class="fas fa-user-circle"></i> Meu Perfil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Flatpickr - Datepicker moderno -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle menu
            $('#menu-toggle').click(function() {
                $('#sidebar').toggleClass('collapsed expanded');
                $('#main-content').toggleClass('expanded');
            });
            
            // Auto-collapse menu on mobile
            if ($(window).width() < 992) {
                $('#sidebar').addClass('collapsed');
                $('#main-content').addClass('expanded');
            }
            
            // Responsive behavior
            $(window).resize(function() {
                if ($(window).width() < 992) {
                    $('#sidebar').addClass('collapsed');
                    $('#main-content').addClass('expanded');
                } else {
                    $('#sidebar').removeClass('collapsed expanded');
                    $('#main-content').removeClass('expanded');
                }
            });
            
            // Modificações para o Flatpickr - Fechar calendário quando clicar fora
            document.addEventListener('click', function(e) {
                if (document.body.classList.contains('flatpickr-open')) {
                    const calendars = document.querySelectorAll('.flatpickr-calendar');
                    let clickedInside = false;
                    
                    // Verificar se o clique foi dentro de algum calendário
                    calendars.forEach(function(calendar) {
                        if (calendar.contains(e.target)) {
                            clickedInside = true;
                        }
                    });
                    
                    // Verificar se o clique foi em um elemento de input
                    const inputs = document.querySelectorAll('.flatpickr-input');
                    inputs.forEach(function(input) {
                        if (input === e.target) {
                            clickedInside = true;
                        }
                    });
                    
                    // Se o clique foi fora, fechar todos os calendários
                    if (!clickedInside) {
                        inputs.forEach(function(input) {
                            if (input._flatpickr && input._flatpickr.isOpen) {
                                input._flatpickr.close();
                            }
                        });
                    }
                }
            });
        });
    </script>
    
    <!-- Mensagens Flash -->
    <?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sucesso',
            text: '<?= session()->getFlashdata('success') ?>',
            timer: 3000,
            showConfirmButton: false,
            customClass: {
                popup: 'swal-custom'
            }
        });
    </script>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: '<?= session()->getFlashdata('error') ?>',
            timer: 3000,
            showConfirmButton: false,
            customClass: {
                popup: 'swal-custom'
            }
        });
    </script>
    <?php endif; ?>
    
    <!-- Scripts específicos da página -->
    <?= $this->renderSection('scripts') ?>
</body>
</html> 