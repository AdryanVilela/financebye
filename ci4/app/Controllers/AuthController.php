<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function login()
    {
        // Se já estiver logado, redirecionar para o dashboard
        if (session()->get('usuario')) {
            return redirect()->to(base_url('dashboard'));
        }
        
        return view('auth/login');
    }
    
    public function logout()
    {
        // Destruir a sessão
        session()->destroy();
        
        return redirect()->to(base_url('login'));
    }
    
    public function recuperarSenha()
    {
        return view('auth/recuperar-senha');
    }

    public function actualizarUltimoAcesso($usuarioId)
    {
        // Atualizar o campo de último acesso
        $db = \Config\Database::connect();
        $data = [
            'ultimo_acesso' => date('Y-m-d H:i:s')
        ];
        
        $db->table('usuarios')
            ->where('id', $usuarioId)
            ->update($data);
    }
} 