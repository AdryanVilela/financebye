<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PerfilController extends Controller
{
    /**
     * Exibe a página de perfil do usuário
     */
    public function index()
    {
        // Verificar se o usuário está logado da mesma forma que os outros controladores
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        // Log para debug
        log_message('debug', 'Usuário acessando página de perfil: ' . json_encode(session()->get('usuario')));
        
        return view('perfil/index', [
            'title' => 'Meu Perfil | FinanceBye'
        ]);
    }
} 