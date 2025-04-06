<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        return view('dashboard/index', [
            'title' => 'Dashboard'
        ]);
    }
    
    public function transacoes()
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        return view('transacoes/index', [
            'title' => 'Transações'
        ]);
    }
    
    public function categorias()
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        return view('categorias/index', [
            'title' => 'Categorias'
        ]);
    }
    
    public function usuarios()
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        // Verificar se o usuário atual é administrador ou tem permissão
        $usuario = session()->get('usuario');
        
        return view('usuarios/index', [
            'title' => 'Usuários'
        ]);
    }
    
    public function empresas()
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        return view('empresas/index', [
            'title' => 'Empresas'
        ]);
    }
    
    public function perfil()
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        // Redirecionar para o novo controlador de perfil
        return redirect()->to(base_url('perfil'));
    }
} 