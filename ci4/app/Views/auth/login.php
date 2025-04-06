<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinanceBye - Login</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --roxo-primary: #693976;
            --roxo-light: #8a5b9a;
            --roxo-dark: #4a2852;
            --cinza-bg: #f8f9fa;
            --accent-color: #ff6b6b;
            --accent-hover: #ff5252;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #f0f2f5;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"%3E%3Cpath fill="%23693976" fill-opacity="0.05" d="M0,224L48,213.3C96,203,192,181,288,154.7C384,128,480,96,576,117.3C672,139,768,213,864,218.7C960,224,1056,160,1152,138.7C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"%3E%3C/path%3E%3C/svg%3E');
            background-size: cover;
            background-position: center bottom;
            background-repeat: no-repeat;
        }
        
        .login-container {
            max-width: 1100px;
            margin: 2rem auto;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }
        
        .login-image {
            flex: 1.2;
            background: linear-gradient(135deg, var(--roxo-primary) 0%, var(--roxo-dark) 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-image::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
            animation: shimmer 8s infinite linear;
            z-index: 1;
        }
        
        @keyframes shimmer {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .login-image h2 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .login-image p {
            font-size: 1.2rem;
            margin-bottom: 30px;
            opacity: 0.9;
            line-height: 1.8;
            position: relative;
            z-index: 2;
        }
        
        .circles {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.1);
            animation: animate 25s linear infinite;
            bottom: -150px;
            border-radius: 50%;
        }
        
        .circles li:nth-child(1) {
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }
        
        .circles li:nth-child(2) {
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
        }
        
        .circles li:nth-child(3) {
            left: 70%;
            width: 20px;
            height: 20px;
            animation-delay: 4s;
        }
        
        .circles li:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
        }
        
        .circles li:nth-child(5) {
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
        }
        
        .circles li:nth-child(6) {
            left: 75%;
            width: 110px;
            height: 110px;
            animation-delay: 3s;
        }
        
        .circles li:nth-child(7) {
            left: 35%;
            width: 150px;
            height: 150px;
            animation-delay: 7s;
        }
        
        .circles li:nth-child(8) {
            left: 50%;
            width: 25px;
            height: 25px;
            animation-delay: 15s;
            animation-duration: 45s;
        }
        
        .circles li:nth-child(9) {
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
            animation-duration: 35s;
        }
        
        .circles li:nth-child(10) {
            left: 85%;
            width: 150px;
            height: 150px;
            animation-delay: 0s;
            animation-duration: 11s;
        }
        
        @keyframes animate {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
                border-radius: 0;
            }
            100% {
                transform: translateY(-1000px) rotate(720deg);
                opacity: 0;
                border-radius: 50%;
            }
        }
        
        .login-form {
            flex: 0.8;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-header {
            margin-bottom: 40px;
            text-align: center;
        }
        
        .login-header h3 {
            color: var(--roxo-primary);
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 15px;
        }
        
        .login-header p {
            color: #777;
            font-size: 1rem;
        }
        
        .form-group {
            position: relative;
            margin-bottom: 28px;
        }
        
        .form-control {
            width: 100%;
            padding: 20px 20px 20px 45px;
            border: none;
            background-color: #f7f9fc;
            border-radius: 12px;
            font-size: 16px;
            color: #333;
            height: auto;
            transition: all 0.3s;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .form-control:focus {
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(105, 57, 118, 0.1), inset 0 1px 3px rgba(0,0,0,0.05);
            outline: none;
        }
        
        .form-icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 15px;
            color: var(--roxo-light);
            font-size: 18px;
        }
        
        .btn-login {
            background: linear-gradient(45deg, var(--roxo-primary), var(--accent-color));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 16px;
            font-weight: 600;
            font-size: 16px;
            margin-top: 20px;
            transition: all 0.4s ease;
            box-shadow: 0 4px 15px rgba(105, 57, 118, 0.2);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn-login:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background: linear-gradient(45deg, var(--accent-color), var(--roxo-primary));
            transition: all 0.4s ease;
            z-index: -1;
        }
        
        .btn-login:hover:before {
            width: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(105, 57, 118, 0.3);
            color: white;
        }
        
        .login-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 0;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: var(--roxo-primary);
            border-color: var(--roxo-primary);
        }
        
        .form-check-label {
            padding-left: 6px;
            cursor: pointer;
            font-size: 15px;
        }
        
        .text-roxo {
            color: var(--roxo-primary);
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .text-roxo:hover {
            color: var(--accent-color);
            text-decoration: underline;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #aaa;
            font-size: 14px;
        }
        
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background-color: #eee;
        }
        
        .divider::before {
            margin-right: 15px;
        }
        
        .divider::after {
            margin-left: 15px;
        }
        
        .social-login {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .social-btn {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 15px;
            font-weight: 500;
            color: #444;
            background-color: #f7f9fc;
            border: none;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .social-btn:hover {
            background-color: #edf1f7;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .social-btn i {
            margin-right: 8px;
            font-size: 18px;
        }
        
        .social-btn.google i {
            color: #db4437;
        }
        
        .social-btn.microsoft i {
            color: #0078d4;
        }
        
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                margin: 20px;
                max-width: 500px;
            }
            
            .login-image {
                padding: 40px 30px;
                text-align: center;
            }
            
            .login-form {
                padding: 40px 30px;
            }
            
            .login-image h2 {
                font-size: 2.2rem;
            }
            
            .login-image p {
                font-size: 1rem;
            }
            
            .social-login {
                flex-direction: column;
                gap: 10px;
            }
        }
        
        @media (max-width: 576px) {
            .login-image, 
            .login-form {
                padding: 30px 20px;
            }
            
            .login-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .login-header h3 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-image d-none d-lg-flex">
                <ul class="circles">
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
                <h2>FinanceBye</h2>
                <p>Transforme sua gestão financeira com um sistema intuitivo, moderno e eficiente. Controle seus gastos, monitore receitas e tome decisões baseadas em dados reais.</p>
                <div class="d-flex mt-auto">
                    <div class="text-white opacity-75">
                        <small>&copy; 2023 FinanceBye - Todos os direitos reservados</small>
                    </div>
                </div>
            </div>
            <div class="login-form">
                <div class="login-header">
                    <h3>Bem-vindo</h3>
                    <p>Entre com suas credenciais para acessar</p>
                </div>
                <form action="<?= base_url('api/login') ?>" method="post" id="loginForm">
                    <div class="form-group">
                        <i class="fas fa-envelope form-icon"></i>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-lock form-icon"></i>
                        <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>
                    <div class="login-footer">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="lembrar">
                            <label class="form-check-label" for="lembrar">Lembrar-me</label>
                        </div>
                        <a href="<?= base_url('recuperar-senha') ?>" class="text-decoration-none text-roxo">Esqueceu a senha?</a>
                    </div>
                    <button type="submit" class="btn btn-login w-100">
                        <i class="fas fa-sign-in-alt me-2"></i>Entrar no Sistema
                    </button>
                </form>
                
                <div class="divider">ou continue com</div>
                
                <div class="social-login">
                    <button type="button" class="social-btn google">
                        <i class="fab fa-google"></i> Google
                    </button>
                    <button type="button" class="social-btn microsoft">
                        <i class="fab fa-microsoft"></i> Microsoft
                    </button>
                </div>
                
                <div class="mt-4 d-block d-lg-none text-center">
                    <h5 class="text-roxo">FinanceBye</h5>
                    <p class="small text-muted">Sistema de Gestão Financeira</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(document).ready(function() {
            // Desabilitar botões de login social temporariamente
            $('.social-btn').click(function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Em breve',
                    text: 'Login social estará disponível em breve!',
                    timer: 2000,
                    showConfirmButton: false,
                    timerProgressBar: true
                });
            });
            
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                
                // Adiciona animação ao botão
                const btnSubmit = $(this).find('button[type="submit"]');
                const originalHtml = btnSubmit.html();
                btnSubmit.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Entrando...');
                btnSubmit.prop('disabled', true);
                
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: JSON.stringify({
                        email: $('#email').val(),
                        senha: $('#senha').val()
                    }),
                    contentType: 'application/json',
                    success: function(response) {
                        if(response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login realizado com sucesso!',
                                text: 'Redirecionando para o dashboard...',
                                timer: 2000,
                                showConfirmButton: false,
                                timerProgressBar: true
                            }).then(function() {
                                window.location.href = '<?= base_url("dashboard") ?>';
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = 'Erro ao fazer login. Tente novamente.';
                        if(xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: errorMsg
                        });
                        
                        // Restaura botão
                        btnSubmit.html(originalHtml);
                        btnSubmit.prop('disabled', false);
                    }
                });
            });
        });
    </script>
</body>
</html> 