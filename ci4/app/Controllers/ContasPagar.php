<?php

namespace App\Controllers;

use App\Models\ContasPagarModel;
use App\Models\CategoriaModel;
use App\Models\TransacaoModel;

class ContasPagar extends BaseController
{
    protected $contasPagarModel;
    protected $categoriaModel;
    protected $transacaoModel;
    
    public function __construct()
    {
        $this->contasPagarModel = new ContasPagarModel();
        $this->categoriaModel = new CategoriaModel();
        $this->transacaoModel = new TransacaoModel();
    }
    
    public function index()
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        // Atualizar status de contas atrasadas
        $this->contasPagarModel->atualizarStatusAtrasado();
        
        // Filtros
        $status = $this->request->getGet('status');
        $data_vencimento = $this->request->getGet('data_vencimento');
        
        $usuario_id = session()->get('usuario_id');
        
        // Aplicar filtros
        $builder = $this->contasPagarModel->where('usuario_id', $usuario_id);
        
        if ($status) {
            $builder->where('status', $status);
        }
        
        if ($data_vencimento) {
            $builder->where('data_vencimento', $data_vencimento);
        }
        
        $contas = $builder->orderBy('data_vencimento', 'ASC')->findAll();
        
        // Calcular totais
        $totais = $this->contasPagarModel->getTotaisPorStatus($usuario_id);
        
        $data = [
            'titulo' => 'Contas a Pagar',
            'contas' => $contas,
            'totalPago' => $totais['pago'],
            'totalPendente' => $totais['pendente'],
            'totalAtrasado' => $totais['atrasado'],
            'totalGeral' => $totais['total']
        ];
        
        return view('contas_pagar/index', $data);
    }
    
    public function novo()
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        $categorias = $this->categoriaModel->where('tipo', 'despesa')
                                           ->where('usuario_id', session()->get('usuario_id'))
                                           ->findAll();
        
        $data = [
            'titulo' => 'Nova Conta a Pagar',
            'categorias' => $categorias
        ];
        
        return view('contas_pagar/form', $data);
    }
    
    public function editar($id = null)
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        if (!$id) {
            return redirect()->to('/contas-pagar');
        }
        
        $conta = $this->contasPagarModel->find($id);
        
        // Verificar se a conta pertence ao usuário
        if ($conta['usuario_id'] != session()->get('usuario_id')) {
            return redirect()->to('/contas-pagar');
        }
        
        $categorias = $this->categoriaModel->where('tipo', 'despesa')
                                           ->where('usuario_id', session()->get('usuario_id'))
                                           ->findAll();
        
        $data = [
            'titulo' => 'Editar Conta a Pagar',
            'conta' => $conta,
            'categorias' => $categorias
        ];
        
        return view('contas_pagar/form', $data);
    }
    
    public function salvar()
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        $id = $this->request->getPost('id');
        
        $data = [
            'descricao' => $this->request->getPost('descricao'),
            'valor' => $this->request->getPost('valor'),
            'data_emissao' => $this->request->getPost('data_emissao'),
            'data_vencimento' => $this->request->getPost('data_vencimento'),
            'status' => $this->request->getPost('status'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'observacoes' => $this->request->getPost('observacoes'),
            'usuario_id' => session()->get('usuario_id')
        ];
        
        // Se for marcado como pago e não tiver data de pagamento, setar a data atual
        if ($data['status'] == 'pago' && empty($this->request->getPost('data_pagamento'))) {
            $data['data_pagamento'] = date('Y-m-d');
        } else if ($data['status'] == 'pago') {
            $data['data_pagamento'] = $this->request->getPost('data_pagamento');
        }
        
        if ($id) {
            // Verificar se a conta pertence ao usuário
            $conta = $this->contasPagarModel->find($id);
            if ($conta['usuario_id'] != session()->get('usuario_id')) {
                return redirect()->to('/contas-pagar');
            }
            
            // Checar se está sendo marcada como paga agora
            $statusAntigo = $conta['status'];
            $novoStatus = $data['status'];
            
            $this->contasPagarModel->update($id, $data);
            
            // Se mudou de pendente/atrasado para pago, criar transação
            if (($statusAntigo == 'pendente' || $statusAntigo == 'atrasado') && $novoStatus == 'pago') {
                $this->criarTransacao($id);
            }
            
            session()->setFlashdata('mensagem', 'Conta atualizada com sucesso!');
        } else {
            $this->contasPagarModel->insert($data);
            
            // Se já estiver marcada como paga, criar transação
            if ($data['status'] == 'pago') {
                $id = $this->contasPagarModel->getInsertID();
                $this->criarTransacao($id);
            }
            
            session()->setFlashdata('mensagem', 'Conta cadastrada com sucesso!');
        }
        
        return redirect()->to('/contas-pagar');
    }
    
    public function baixar($id = null)
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        if (!$id) {
            return redirect()->to('/contas-pagar');
        }
        
        $conta = $this->contasPagarModel->find($id);
        
        // Verificar se a conta pertence ao usuário
        if ($conta['usuario_id'] != session()->get('usuario_id')) {
            return redirect()->to('/contas-pagar');
        }
        
        // Atualizar status e data de pagamento
        $this->contasPagarModel->update($id, [
            'status' => 'pago',
            'data_pagamento' => date('Y-m-d')
        ]);
        
        // Criar transação
        $this->criarTransacao($id);
        
        session()->setFlashdata('mensagem', 'Conta paga com sucesso!');
        
        return redirect()->to('/contas-pagar');
    }
    
    protected function criarTransacao($conta_id)
    {
        $conta = $this->contasPagarModel->find($conta_id);
        
        // Verificar se já existe uma transação para esta conta
        $transacaoExistente = $this->transacaoModel->where('fonte_id', $conta_id)
                                                 ->where('fonte_tipo', 'conta_pagar')
                                                 ->first();
        
        if ($transacaoExistente) {
            return; // Já existe transação, não criar outra
        }
        
        $dataTransacao = [
            'descricao' => $conta['descricao'],
            'valor' => -1 * abs($conta['valor']), // Valor negativo para despesa
            'data' => $conta['data_pagamento'] ?? date('Y-m-d'),
            'tipo' => 'despesa',
            'categoria_id' => $conta['categoria_id'],
            'usuario_id' => $conta['usuario_id'],
            'fonte_id' => $conta_id,
            'fonte_tipo' => 'conta_pagar',
            'observacoes' => 'Gerado automaticamente a partir de Contas a Pagar'
        ];
        
        $this->transacaoModel->insert($dataTransacao);
    }
    
    public function excluir($id = null)
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        if (!$id) {
            return redirect()->to('/contas-pagar');
        }
        
        $conta = $this->contasPagarModel->find($id);
        
        // Verificar se a conta pertence ao usuário
        if ($conta['usuario_id'] != session()->get('usuario_id')) {
            return redirect()->to('/contas-pagar');
        }
        
        // Excluir transação relacionada
        $this->transacaoModel->where('fonte_id', $id)
                             ->where('fonte_tipo', 'conta_pagar')
                             ->delete();
        
        // Excluir conta
        $this->contasPagarModel->delete($id);
        
        session()->setFlashdata('mensagem', 'Conta excluída com sucesso!');
        
        return redirect()->to('/contas-pagar');
    }
} 