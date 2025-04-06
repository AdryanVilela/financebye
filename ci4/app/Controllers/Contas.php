<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Contas extends BaseController
{
    protected $contasReceberModel;
    protected $contasPagarModel;
    protected $categoriaModel;
    protected $clienteModel;
    protected $fornecedorModel;
    protected $transacaoModel;

    public function __construct()
    {
        $this->contasReceberModel = new \App\Models\ContasReceberModel();
        $this->contasPagarModel = new \App\Models\ContasPagarModel();
        $this->categoriaModel = new \App\Models\CategoriaModel();
        $this->clienteModel = new \App\Models\ClienteModel();
        $this->fornecedorModel = new \App\Models\FornecedorModel();
        $this->transacaoModel = new \App\Models\TransacaoModel();
    }

    public function index()
    {
        // Verificar login
        if (!session()->get('usuario_id')) {
            return redirect()->to(site_url('login'));
        }

        $tipo = $this->request->getGet('tipo') ?? 'receber';
        $usuario_id = session()->get('usuario_id');
        
        // Atualizar status de contas atrasadas
        if ($tipo == 'receber') {
            $this->contasReceberModel->atualizarStatusAtrasado($usuario_id);
        } else {
            $this->contasPagarModel->atualizarStatusAtrasado($usuario_id);
        }
        
        // Filtros
        $status = $this->request->getGet('status');
        $dataInicio = $this->request->getGet('data_inicio');
        $dataFim = $this->request->getGet('data_fim');
        $entidade = $this->request->getGet('entidade');
        
        // Buscar contas
        if ($tipo == 'receber') {
            $contas = $this->contasReceberModel->getByPeriodo($usuario_id, $dataInicio, $dataFim, $status);
            if ($entidade) {
                $contas = array_filter($contas, function($conta) use ($entidade) {
                    return stripos($conta['cliente_nome'], $entidade) !== false;
                });
            }
            $totais = $this->contasReceberModel->getTotaisPorStatus($usuario_id);
        } else {
            $contas = $this->contasPagarModel->getByPeriodo($usuario_id, $dataInicio, $dataFim, $status);
            if ($entidade) {
                $contas = array_filter($contas, function($conta) use ($entidade) {
                    return stripos($conta['fornecedor_nome'], $entidade) !== false;
                });
            }
            $totais = $this->contasPagarModel->getTotaisPorStatus($usuario_id);
        }

        $data = [
            'tipo' => $tipo,
            'contas' => $contas,
            'totais' => $totais,
            'filtros' => [
                'status' => $status,
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
                'entidade' => $entidade
            ]
        ];

        return view('contas/index', $data);
    }

    public function novo()
    {
        // Verificar login
        if (!session()->get('usuario_id')) {
            return redirect()->to(site_url('login'));
        }

        $tipo = $this->request->getGet('tipo') ?? 'receber';
        $usuario_id = session()->get('usuario_id');
        
        // Buscar categorias
        $categorias = $this->categoriaModel->where('usuario_id', $usuario_id)
                                          ->where('tipo', $tipo == 'receber' ? 'receita' : 'despesa')
                                          ->findAll();
        
        // Buscar entidades (clientes ou fornecedores)
        if ($tipo == 'receber') {
            $entidades = $this->clienteModel->where('usuario_id', $usuario_id)->findAll();
        } else {
            $entidades = $this->fornecedorModel->where('usuario_id', $usuario_id)->findAll();
        }
        
        $data = [
            'tipo' => $tipo,
            'categorias' => $categorias,
            'entidades' => $entidades
        ];
        
        return view('contas/form', $data);
    }

    public function editar($id = null)
    {
        // Verificar login
        if (!session()->get('usuario_id')) {
            return redirect()->to(site_url('login'));
        }
        
        if (!$id) {
            return redirect()->to(site_url('contas'));
        }
        
        $tipo = $this->request->getGet('tipo') ?? 'receber';
        $usuario_id = session()->get('usuario_id');
        
        // Buscar conta
        if ($tipo == 'receber') {
            $conta = $this->contasReceberModel->find($id);
            // Verificar se a conta pertence ao usuário
            if (!$conta || $conta['usuario_id'] != $usuario_id) {
                return redirect()->to(site_url('contas?tipo=receber'));
            }
        } else {
            $conta = $this->contasPagarModel->find($id);
            // Verificar se a conta pertence ao usuário
            if (!$conta || $conta['usuario_id'] != $usuario_id) {
                return redirect()->to(site_url('contas?tipo=pagar'));
            }
        }
        
        // Buscar categorias
        $categorias = $this->categoriaModel->where('usuario_id', $usuario_id)
                                          ->where('tipo', $tipo == 'receber' ? 'receita' : 'despesa')
                                          ->findAll();
        
        // Buscar entidades (clientes ou fornecedores)
        if ($tipo == 'receber') {
            $entidades = $this->clienteModel->where('usuario_id', $usuario_id)->findAll();
        } else {
            $entidades = $this->fornecedorModel->where('usuario_id', $usuario_id)->findAll();
        }
        
        $data = [
            'tipo' => $tipo,
            'conta' => $conta,
            'categorias' => $categorias,
            'entidades' => $entidades
        ];
        
        return view('contas/form', $data);
    }

    public function salvar()
    {
        // Verificar login
        if (!session()->get('usuario_id')) {
            return redirect()->to(site_url('login'));
        }
        
        $usuario_id = session()->get('usuario_id');
        $tipo = $this->request->getPost('tipo');
        $id = $this->request->getPost('id');
        
        $data = [
            'descricao' => $this->request->getPost('descricao'),
            'valor' => str_replace(',', '.', $this->request->getPost('valor')),
            'data_emissao' => $this->request->getPost('data_emissao'),
            'data_vencimento' => $this->request->getPost('data_vencimento'),
            'status' => $this->request->getPost('status'),
            'categoria_id' => $this->request->getPost('categoria_id') ? $this->request->getPost('categoria_id') : null,
            'observacoes' => $this->request->getPost('observacoes'),
            'usuario_id' => $usuario_id
        ];
        
        // Adicionar campo data_pagamento se status for concluido
        if ($data['status'] == 'concluido') {
            $data['data_pagamento'] = $this->request->getPost('data_pagamento') ? $this->request->getPost('data_pagamento') : date('Y-m-d');
        } else {
            $data['data_pagamento'] = null;
        }
        
        // Adicionar cliente_id ou fornecedor_id
        $entidade_id = $this->request->getPost('entidade_id');
        if ($tipo == 'receber') {
            $data['cliente_id'] = $entidade_id;
            $model = $this->contasReceberModel;
        } else {
            $data['fornecedor_id'] = $entidade_id;
            $model = $this->contasPagarModel;
        }
        
        // Salvar no banco
        if ($id) {
            // Atualizar
            $model->update($id, $data);
            
            // Se status for concluido, criar transação
            if ($data['status'] == 'concluido') {
                $this->criarTransacao($id, $tipo);
            }
            
            session()->setFlashdata('success', 'Conta atualizada com sucesso!');
        } else {
            // Inserir
            $id = $model->insert($data);
            
            // Se status for concluido, criar transação
            if ($data['status'] == 'concluido') {
                $this->criarTransacao($id, $tipo);
            }
            
            session()->setFlashdata('success', 'Conta cadastrada com sucesso!');
        }
        
        return redirect()->to(site_url('contas?tipo=' . $tipo));
    }

    public function baixar($id = null)
    {
        // Verificar login
        if (!session()->get('usuario_id')) {
            return redirect()->to(site_url('login'));
        }
        
        if (!$id) {
            return redirect()->to(site_url('contas'));
        }
        
        $tipo = $this->request->getGet('tipo') ?? 'receber';
        $usuario_id = session()->get('usuario_id');
        
        // Buscar conta
        if ($tipo == 'receber') {
            $model = $this->contasReceberModel;
        } else {
            $model = $this->contasPagarModel;
        }
        
        $conta = $model->find($id);
        
        // Verificar se a conta pertence ao usuário
        if (!$conta || $conta['usuario_id'] != $usuario_id) {
            return redirect()->to(site_url('contas?tipo=' . $tipo));
        }
        
        // Atualizar status e data_pagamento
        $model->update($id, [
            'status' => 'concluido',
            'data_pagamento' => date('Y-m-d')
        ]);
        
        // Criar transação
        $this->criarTransacao($id, $tipo);
        
        session()->setFlashdata('success', $tipo == 'receber' ? 'Conta marcada como recebida com sucesso!' : 'Conta marcada como paga com sucesso!');
        
        return redirect()->to(site_url('contas?tipo=' . $tipo));
    }

    protected function criarTransacao($conta_id, $tipo)
    {
        $usuario_id = session()->get('usuario_id');
        
        // Verificar se já existe transação para esta conta
        $transacaoExistente = $this->transacaoModel->where('tipo_origem', $tipo == 'receber' ? 'conta_receber' : 'conta_pagar')
                                                 ->where('id_origem', $conta_id)
                                                 ->first();
        
        if ($transacaoExistente) {
            return; // Transação já existe, não criar novamente
        }
        
        // Buscar conta
        if ($tipo == 'receber') {
            $conta = $this->contasReceberModel->find($conta_id);
            $tipoTransacao = 'receita';
        } else {
            $conta = $this->contasPagarModel->find($conta_id);
            $tipoTransacao = 'despesa';
        }
        
        // Criar transação
        $data = [
            'descricao' => $conta['descricao'],
            'valor' => $conta['valor'],
            'data' => $conta['data_pagamento'] ?? date('Y-m-d'),
            'tipo' => $tipoTransacao,
            'categoria_id' => $conta['categoria_id'],
            'usuario_id' => $usuario_id,
            'tipo_origem' => $tipo == 'receber' ? 'conta_receber' : 'conta_pagar',
            'id_origem' => $conta_id
        ];
        
        $this->transacaoModel->insert($data);
    }

    public function excluir($id = null)
    {
        // Verificar login
        if (!session()->get('usuario_id')) {
            return redirect()->to(site_url('login'));
        }
        
        if (!$id) {
            return redirect()->to(site_url('contas'));
        }
        
        $tipo = $this->request->getGet('tipo') ?? 'receber';
        $usuario_id = session()->get('usuario_id');
        
        // Selecionar modelo correto
        if ($tipo == 'receber') {
            $model = $this->contasReceberModel;
            $tipoOrigem = 'conta_receber';
        } else {
            $model = $this->contasPagarModel;
            $tipoOrigem = 'conta_pagar';
        }
        
        // Buscar conta
        $conta = $model->find($id);
        
        // Verificar se a conta pertence ao usuário
        if (!$conta || $conta['usuario_id'] != $usuario_id) {
            return redirect()->to(site_url('contas?tipo=' . $tipo));
        }
        
        // Excluir transação relacionada
        $this->transacaoModel->where('tipo_origem', $tipoOrigem)
                           ->where('id_origem', $id)
                           ->delete();
        
        // Excluir conta
        $model->delete($id);
        
        session()->setFlashdata('success', 'Conta excluída com sucesso!');
        
        return redirect()->to(site_url('contas?tipo=' . $tipo));
    }
} 