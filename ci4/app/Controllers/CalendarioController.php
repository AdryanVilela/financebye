<?php

namespace App\Controllers;

use App\Models\TransacaoModel;
use App\Models\MetaModel;
use App\Models\CategoriaModel;

class CalendarioController extends BaseController
{
    protected $transacoes;
    protected $metas;
    protected $categorias;
    
    public function __construct()
    {
        $this->transacoes = new TransacaoModel();
        $this->metas = new MetaModel();
        $this->categorias = new CategoriaModel();
    }
    
    public function index()
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('usuario')['id'];
        
        // Obter o ID da empresa do usuário
        $empresaId = session()->get('usuario')['empresa_id'];
        
        // Obter data atual e mês/ano selecionados (ou usar o atual)
        $mesAtual = $this->request->getGet('mes') ?? date('m');
        $anoAtual = $this->request->getGet('ano') ?? date('Y');
        
        // Construir data de início e fim do mês
        $dataInicio = $anoAtual . '-' . $mesAtual . '-01';
        $dataFim = date('Y-m-t', strtotime($dataInicio));
        
        // Obter transações do período
        $transacoes = $this->transacoes->where('usuario_id', $usuarioId)
                                       ->where('data >=', $dataInicio)
                                       ->where('data <=', $dataFim)
                                       ->orderBy('data', 'ASC')
                                       ->findAll();
        
        // Obter metas com vencimento no período
        $metas = $this->metas->where('usuario_id', $usuarioId)
                             ->where('data_alvo >=', $dataInicio)
                             ->where('data_alvo <=', $dataFim)
                             ->findAll();
        
        // Preparar dados para a view
        $data = [
            'title' => 'Calendário Financeiro',
            'mes_atual' => $mesAtual,
            'ano_atual' => $anoAtual,
            'transacoes' => $transacoes,
            'metas' => $metas,
            'categorias' => $this->categorias->where('empresa_id', $empresaId)->findAll(),
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim
        ];
        
        // Renderizar a view
        return view('calendario/index', $data);
    }
    
    public function eventos()
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return $this->response->setJSON(['error' => 'Usuário não autenticado']);
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('usuario')['id'];
        
        // Obter parâmetros de data start e end
        $inicio = $this->request->getGet('start');
        $fim = $this->request->getGet('end');
        
        if (!$inicio || !$fim) {
            return $this->response->setJSON([]);
        }
        
        // Obter transações do período
        $transacoes = $this->transacoes->where('usuario_id', $usuarioId)
                                      ->where('data >=', $inicio)
                                      ->where('data <=', $fim)
                                      ->findAll();
        
        // Obter metas do período
        $metas = $this->metas->where('usuario_id', $usuarioId)
                            ->where('data_alvo >=', $inicio)
                            ->where('data_alvo <=', $fim)
                            ->findAll();
        
        // Formatar eventos para o calendário
        $eventos = [];
        
        // Adicionar transações como eventos
        foreach ($transacoes as $transacao) {
            $cor = ($transacao['valor'] >= 0) ? '#28a745' : '#dc3545';
            $titulo = $transacao['descricao'];
            $categoria = '';
            
            // Buscar nome da categoria
            if (!empty($transacao['categoria_id'])) {
                $categoriaModel = $this->categorias;
                $categoriaInfo = $categoriaModel->find($transacao['categoria_id']);
                if ($categoriaInfo) {
                    $categoria = $categoriaInfo['nome'];
                    $titulo = $categoria . ': ' . $titulo;
                }
            }
            
            $eventos[] = [
                'id' => 't_' . $transacao['id'],
                'title' => $titulo,
                'start' => $transacao['data'],
                'allDay' => true,
                'backgroundColor' => $cor,
                'borderColor' => $cor,
                'extendedProps' => [
                    'tipo' => 'transacao',
                    'valor' => $transacao['valor'],
                    'categoria' => $categoria,
                    'descricao' => $transacao['descricao']
                ]
            ];
        }
        
        // Adicionar metas como eventos
        foreach ($metas as $meta) {
            $eventos[] = [
                'id' => 'm_' . $meta['id'],
                'title' => 'Meta: ' . $meta['titulo'],
                'start' => $meta['data_alvo'],
                'allDay' => true,
                'backgroundColor' => '#6f42c1',
                'borderColor' => '#6f42c1',
                'extendedProps' => [
                    'tipo' => 'meta',
                    'valor_atual' => $meta['valor_atual'],
                    'valor_alvo' => $meta['valor_alvo'],
                    'titulo' => $meta['titulo'],
                    'descricao' => $meta['descricao'],
                    'progresso' => ($meta['valor_alvo'] > 0) ? min(100, ($meta['valor_atual'] / $meta['valor_alvo']) * 100) : 0
                ]
            ];
        }
        
        return $this->response->setJSON($eventos);
    }
} 