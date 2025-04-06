<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Calendario extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        // Verificar login - usando o mesmo padrão que os outros controllers
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        // Carregar dados para o template
        $data = [
            'title' => 'Calendário Financeiro'
        ];
        
        return view('calendario/index', $data);
    }
    
    public function eventos()
    {
        // Verificar login - usando o mesmo padrão que os outros controllers
        if (!session()->get('usuario')) {
            return $this->failUnauthorized('Usuário não logado');
        }
        
        // Obter ID do usuário
        $usuarioId = session()->get('usuario')['id'];
        
        // Obter parâmetros de data (se existirem)
        $start = $this->request->getGet('start') ?? date('Y-m-01');
        $end = $this->request->getGet('end') ?? date('Y-m-t', strtotime($start));
        
        // Array para armazenar eventos
        $eventos = [];
        
        // Obter transações
        $transacaoModel = new \App\Models\TransacaoModel();
        $transacoes = $transacaoModel->where('usuario_id', $usuarioId)
                                    ->where('data >=', $start)
                                    ->where('data <=', $end)
                                    ->join('categorias', 'categorias.id = transacoes.categoria_id', 'left')
                                    ->select('transacoes.*, categorias.nome as categoria_nome')
                                    ->findAll();
        
        // Processar transações
        foreach ($transacoes as $transacao) {
            $eventos[] = [
                'id' => 't_' . $transacao['id'], // Prefixo 't_' para indicar transação
                'title' => $transacao['descricao'] ?: ($transacao['valor'] >= 0 ? 'Receita' : 'Despesa'),
                'start' => $transacao['data'],
                'allDay' => true,
                'extendedProps' => [
                    'tipo' => 'transacao',
                    'valor' => $transacao['valor'],
                    'categoria' => $transacao['categoria_nome'] ?: 'Sem categoria',
                    'descricao' => $transacao['descricao'] ?: 'Sem descrição'
                ]
            ];
        }
        
        // Obter metas
        $metaModel = new \App\Models\MetaModel();
        $metas = $metaModel->where('usuario_id', $usuarioId)
                          ->where('data_alvo >=', $start)
                          ->where('data_alvo <=', $end)
                          ->join('categorias', 'categorias.id = metas.categoria_id', 'left')
                          ->select('metas.*, categorias.nome as categoria_nome')
                          ->findAll();
        
        // Processar metas
        foreach ($metas as $meta) {
            // Calcular progresso
            $valorAtual = $meta['valor_atual'] ?: 0;
            $valorAlvo = $meta['valor_alvo'] ?: 1; // Evitar divisão por zero
            $progresso = min(100, ($valorAtual / $valorAlvo) * 100);
            
            $eventos[] = [
                'id' => 'm_' . $meta['id'], // Prefixo 'm_' para indicar meta
                'title' => $meta['titulo'],
                'start' => $meta['data_alvo'],
                'allDay' => true,
                'extendedProps' => [
                    'tipo' => 'meta',
                    'titulo' => $meta['titulo'],
                    'valor_atual' => $valorAtual,
                    'valor_alvo' => $valorAlvo, 
                    'progresso' => $progresso,
                    'categoria' => $meta['categoria_nome'] ?: 'Sem categoria',
                    'descricao' => $meta['descricao'] ?: 'Sem descrição'
                ]
            ];
        }
        
        // Retornar eventos como JSON
        return $this->respond($eventos);
    }
} 