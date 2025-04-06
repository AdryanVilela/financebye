<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');
$routes->get('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->get('recuperar-senha', 'AuthController::recuperarSenha');

// Rotas do Dashboard
$routes->get('dashboard', 'DashboardController::index');
$routes->get('transacoes', 'DashboardController::transacoes');
$routes->get('categorias', 'DashboardController::categorias');
$routes->get('usuarios', 'DashboardController::usuarios');
$routes->get('empresas', 'DashboardController::empresas');
$routes->get('metas', 'MetaController::index');

// Rotas de Transferências
$routes->get('transferencias', 'TransferenciaController::index');
$routes->get('transferencias/nova', 'TransferenciaController::nova');
$routes->post('transferencias/nova', 'TransferenciaController::nova');
$routes->get('transferencias/detalhes/(:num)', 'TransferenciaController::detalhes/$1');
$routes->get('transferencias/excluir/(:num)', 'TransferenciaController::excluir/$1');
$routes->post('transferencias/filtrar', 'TransferenciaController::filtrar');
$routes->post('transferencias/estatisticas', 'TransferenciaController::estatisticas');

// Rotas de Metas Financeiras
$routes->get('metas/nova', 'MetaController::nova');
$routes->post('metas/nova', 'MetaController::nova');
$routes->get('metas/editar/(:num)', 'MetaController::editar/$1');
$routes->post('metas/editar/(:num)', 'MetaController::editar/$1');
$routes->get('metas/detalhes/(:num)', 'MetaController::detalhes/$1');
$routes->get('metas/excluir/(:num)', 'MetaController::excluir/$1');
$routes->post('metas/atualizar/(:num)', 'MetaController::atualizar/$1');

// Rotas para API
$routes->group('api', function ($routes) {
    // Empresas
    $routes->get('empresas', 'EmpresaController::index');
    $routes->get('empresas/(:num)', 'EmpresaController::show/$1');
    $routes->post('empresas', 'EmpresaController::create');
    $routes->put('empresas/(:num)', 'EmpresaController::update/$1');
    $routes->delete('empresas/(:num)', 'EmpresaController::delete/$1');
    
    // Usuários
    $routes->get('usuarios', 'UsuarioController::index');
    $routes->get('usuarios/(:num)', 'UsuarioController::show/$1');
    $routes->post('usuarios', 'UsuarioController::create');
    $routes->put('usuarios/(:num)', 'UsuarioController::update/$1');
    $routes->delete('usuarios/(:num)', 'UsuarioController::delete/$1');
    $routes->post('login', 'UsuarioController::login');
    $routes->post('logout', 'UsuarioController::logout');
    
    // Categorias
    $routes->get('categorias', 'CategoriaController::index');
    $routes->get('categorias/(:num)', 'CategoriaController::show/$1');
    $routes->post('categorias', 'CategoriaController::create');
    $routes->put('categorias/(:num)', 'CategoriaController::update/$1');
    $routes->delete('categorias/(:num)', 'CategoriaController::delete/$1');
    
    // Transações
    $routes->get('transacoes', 'TransacaoController::index');
    $routes->get('transacoes/(:num)', 'TransacaoController::show/$1');
    $routes->post('transacoes', 'TransacaoController::create');
    $routes->put('transacoes/(:num)', 'TransacaoController::update/$1');
    $routes->delete('transacoes/(:num)', 'TransacaoController::delete/$1');
    $routes->get('transacoes/resumo', 'TransacaoController::resumo');
    $routes->get('transacoes/despesas', 'TransacaoController::listarDespesas');
    $routes->get('transacoes/receitas', 'TransacaoController::listarReceitas');
});

// Perfil
$routes->get('perfil', 'PerfilController::index');
$routes->get('api/perfil', 'Api\PerfilController::index');
$routes->post('api/perfil', 'Api\PerfilController::update');
$routes->post('api/perfil/senha', 'Api\PerfilController::alterarSenha');

// Rotas para Calendário
$routes->get('calendario', 'Calendario::index');
$routes->get('calendario/eventos', 'Calendario::eventos');

// Rotas para Contas a Receber
$routes->get('contas-receber', 'ContasReceber::index');
$routes->get('contas-receber/novo', 'ContasReceber::novo');
$routes->get('contas-receber/editar/(:num)', 'ContasReceber::editar/$1');
$routes->post('contas-receber/salvar', 'ContasReceber::salvar');
$routes->get('contas-receber/baixar/(:num)', 'ContasReceber::baixar/$1');
$routes->get('contas-receber/excluir/(:num)', 'ContasReceber::excluir/$1');

// Rotas para Contas a Pagar
$routes->get('contas-pagar', 'ContasPagar::index');
$routes->get('contas-pagar/novo', 'ContasPagar::novo');
$routes->get('contas-pagar/editar/(:num)', 'ContasPagar::editar/$1');
$routes->post('contas-pagar/salvar', 'ContasPagar::salvar');
$routes->get('contas-pagar/baixar/(:num)', 'ContasPagar::baixar/$1');
$routes->get('contas-pagar/excluir/(:num)', 'ContasPagar::excluir/$1');

// Rotas para Clientes
$routes->get('clientes', 'Cliente::index');
$routes->get('clientes/novo', 'Cliente::novo');
$routes->get('clientes/editar/(:num)', 'Cliente::editar/$1');
$routes->post('clientes/salvar', 'Cliente::salvar');
$routes->get('clientes/excluir/(:num)', 'Cliente::excluir/$1');

// Rotas para Fornecedores
$routes->get('fornecedores', 'Fornecedor::index');
$routes->get('fornecedor/obter/(:num)', 'Fornecedor::obter/$1');
$routes->post('fornecedor/salvar', 'Fornecedor::salvar');
$routes->get('fornecedor/excluir/(:num)', 'Fornecedor::excluir/$1');

// Rotas para Contas (unificado)
$routes->get('contas', 'Conta::index');
$routes->get('contas/novo', 'Conta::novo');
$routes->get('contas/editar/(:num)', 'Conta::editar/$1');
$routes->post('contas/salvar', 'Conta::salvar');
$routes->get('contas/baixar/(:num)', 'Conta::baixar/$1');
$routes->get('contas/excluir/(:num)', 'Conta::excluir/$1');
