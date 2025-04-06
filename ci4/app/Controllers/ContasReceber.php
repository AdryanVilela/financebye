<?php

namespace App\Controllers;

use App\Models\ContasReceberModel;
use App\Models\CategoriaModel;
use App\Models\TransacaoModel;

class ContasReceber extends BaseController
{
    protected $contasReceberModel;
    protected $categoriaModel;
    protected $transacaoModel;
    
    public function __construct()
    {
        $this->contasReceberModel = new ContasReceberModel();
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
        $this->contasReceberModel->atualizarStatusAtrasado();
        
        // Filtros
        $status = $this->request->getGet('status');
        $data_vencimento = $this->request->getGet('data_vencimento');
        $cliente = $this->request->getGet('cliente');
        
        $usuario_id = session()->get('usuario_id');
        
        // Aplicar filtros
        $builder = $this->contasReceberModel->where('usuario_id', $usuario_id);
        
        if ($status) {
            $builder->where('status', $status);
        }
        
        if ($data_vencimento) {
            $builder->where('data_vencimento', $data_vencimento);
        }
        
        if ($cliente) {
            $builder->like('cliente', $cliente);
        }
        
        $contas = $builder->orderBy('data_vencimento', 'ASC')->findAll();
        
        // Calcular totais
        $totais = $this->contasReceberModel->getTotaisPorStatus($usuario_id);
        
        $data = [
            'titulo' => 'Contas a Receber',
            'contas' => $contas,
            'totalRecebido' => $totais['recebido'],
            'totalPendente' => $totais['pendente'],
            'totalAtrasado' => $totais['atrasado'],
            'totalGeral' => $totais['total']
        ];
        
        return view('contas_receber/index', $data);
    }
    
    public function novo()
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        $categorias = $this->categoriaModel->where('tipo', 'receita')
                                           ->where('usuario_id', session()->get('usuario_id'))
                                           ->findAll();
        
        $data = [
            'titulo' => 'Nova Conta a Receber',
            'categorias' => $categorias
        ];
        
        return view('contas_receber/form', $data);
    }
    
    public function editar($id = null)
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        if (!$id) {
            return redirect()->to('/contas-receber');
        }
        
        $conta = $this->contasReceberModel->find($id);
        
        // Verificar se a conta pertence ao usuário
        if ($conta['usuario_id'] != session()->get('usuario_id')) {
            return redirect()->to('/contas-receber');
        }
        
        $categorias = $this->categoriaModel->where('tipo', 'receita')
                                           ->where('usuario_id', session()->get('usuario_id'))
                                           ->findAll();
        
        $data = [
            'titulo' => 'Editar Conta a Receber',
            'conta' => $conta,
            'categorias' => $categorias
        ];
        
        return view('contas_receber/form', $data);
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
            'cliente' => $this->request->getPost('cliente'),
            'valor' => $this->request->getPost('valor'),
            'data_emissao' => $this->request->getPost('data_emissao'),
            'data_vencimento' => $this->request->getPost('data_vencimento'),
            'status' => $this->request->getPost('status'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'observacoes' => $this->request->getPost('observacoes'),
            'usuario_id' => session()->get('usuario_id')
        ];
        
        // Se for marcado como recebido e não tiver data de recebimento, setar a data atual
        if ($data['status'] == 'recebido' && empty($this->request->getPost('data_recebimento'))) {
            $data['data_recebimento'] = date('Y-m-d');
        } else if ($data['status'] == 'recebido') {
            $data['data_recebimento'] = $this->request->getPost('data_recebimento');
        }
        
        if ($id) {
            // Verificar se a conta pertence ao usuário
            $conta = $this->contasReceberModel->find($id);
            if ($conta['usuario_id'] != session()->get('usuario_id')) {
                return redirect()->to('/contas-receber');
            }
            
            // Checar se está sendo marcada como recebida agora
            $statusAntigo = $conta['status'];
            $novoStatus = $data['status'];
            
            $this->contasReceberModel->update($id, $data);
            
            // Se mudou de pendente/atrasado para recebido, criar transação
            if (($statusAntigo == 'pendente' || $statusAntigo == 'atrasado') && $novoStatus == 'recebido') {
                $this->criarTransacao($id);
            }
            
            session()->setFlashdata('mensagem', 'Conta atualizada com sucesso!');
        } else {
            $this->contasReceberModel->insert($data);
            
            // Se já estiver marcada como recebida, criar transação
            if ($data['status'] == 'recebido') {
                $id = $this->contasReceberModel->getInsertID();
                $this->criarTransacao($id);
            }
            
            session()->setFlashdata('mensagem', 'Conta cadastrada com sucesso!');
        }
        
        return redirect()->to('/contas-receber');
    }
    
    public function baixar($id = null)
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        if (!$id) {
            return redirect()->to('/contas-receber');
        }
        
        $conta = $this->contasReceberModel->find($id);
        
        // Verificar se a conta pertence ao usuário
        if ($conta['usuario_id'] != session()->get('usuario_id')) {
            return redirect()->to('/contas-receber');
        }
        
        // Atualizar status e data de recebimento
        $this->contasReceberModel->update($id, [
            'status' => 'recebido',
            'data_recebimento' => date('Y-m-d')
        ]);
        
        // Criar transação
        $this->criarTransacao($id);
        
        session()->setFlashdata('mensagem', 'Conta recebida com sucesso!');
        
        return redirect()->to('/contas-receber');
    }
    
    protected function criarTransacao($conta_id)
    {
        $conta = $this->contasReceberModel->find($conta_id);
        
        // Verificar se já existe uma transação para esta conta
        $transacaoExistente = $this->transacaoModel->where('fonte_id', $conta_id)
                                                 ->where('fonte_tipo', 'conta_receber')
                                                 ->first();
        
        if ($transacaoExistente) {
            return; // Já existe transação, não criar outra
        }
        
        $dataTransacao = [
            'descricao' => $conta['descricao'],
            'valor' => $conta['valor'],
            'data' => $conta['data_recebimento'] ?? date('Y-m-d'),
            'tipo' => 'receita',
            'categoria_id' => $conta['categoria_id'],
            'usuario_id' => $conta['usuario_id'],
            'fonte_id' => $conta_id,
            'fonte_tipo' => 'conta_receber',
            'observacoes' => 'Gerado automaticamente a partir de Contas a Receber'
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
            return redirect()->to('/contas-receber');
        }
        
        $conta = $this->contasReceberModel->find($id);
        
        // Verificar se a conta pertence ao usuário
        if ($conta['usuario_id'] != session()->get('usuario_id')) {
            return redirect()->to('/contas-receber');
        }
        
        // Excluir transação relacionada
        $this->transacaoModel->where('fonte_id', $id)
                             ->where('fonte_tipo', 'conta_receber')
                             ->delete();
        
        // Excluir conta
        $this->contasReceberModel->delete($id);
        
        session()->setFlashdata('mensagem', 'Conta excluída com sucesso!');
        
        return redirect()->to('/contas-receber');
    }
} 