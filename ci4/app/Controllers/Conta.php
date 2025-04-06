<?php

namespace App\Controllers;

use App\Models\ContaModel;
use App\Models\ClienteModel;
use App\Models\FornecedorModel;
use App\Models\CategoriaModel;
use App\Models\TransacaoModel;

class Conta extends BaseController
{
    protected $contaModel;
    protected $clienteModel;
    protected $fornecedorModel;
    protected $categoriaModel;
    protected $transacaoModel;
    
    public function __construct()
    {
        $this->contaModel = new ContaModel();
        $this->clienteModel = new ClienteModel();
        $this->fornecedorModel = new FornecedorModel();
        $this->categoriaModel = new CategoriaModel();
        $this->transacaoModel = new TransacaoModel();
    }
    
    public function index()
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        $usuario_id = session()->get('usuario_id');
        $empresa_id = session()->get('empresa_id');
        
        // Atualizar status de contas atrasadas
        $this->contaModel->atualizarStatusAtrasado($usuario_id, $empresa_id);
        
        // Filtros
        $tipo = $this->request->getGet('tipo') ?: 'receber';
        $status = $this->request->getGet('status');
        $data_vencimento = $this->request->getGet('data_vencimento');
        $entidade_id = $this->request->getGet('entidade_id');
        
        // Aplicar filtros
        $builder = $this->contaModel->where('usuario_id', $usuario_id)
                                    ->where('empresa_id', $empresa_id)
                                    ->where('tipo', $tipo);
        
        if ($status) {
            $builder->where('status', $status);
        }
        
        if ($data_vencimento) {
            $builder->where('data_vencimento', $data_vencimento);
        }
        
        if ($entidade_id) {
            if ($tipo == 'receber') {
                $builder->where('cliente_id', $entidade_id);
            } else {
                $builder->where('fornecedor_id', $entidade_id);
            }
        }
        
        $contas = $builder->orderBy('data_vencimento', 'ASC')->findAll();
        
        // Buscar nomes de clientes/fornecedores e categorias
        $contas = $this->enriquecerDadosContas($contas, $usuario_id, $empresa_id);
        
        // Calcular totais
        $totais = $this->contaModel->getTotaisPorStatus($tipo, $usuario_id, $empresa_id);
        
        // Buscar lista de clientes ou fornecedores para o filtro
        if ($tipo == 'receber') {
            $entidades = $this->clienteModel->where('usuario_id', $usuario_id)
                                           ->where('empresa_id', $empresa_id)
                                           ->orderBy('nome', 'ASC')
                                           ->findAll();
        } else {
            $entidades = $this->fornecedorModel->where('usuario_id', $usuario_id)
                                               ->where('empresa_id', $empresa_id)
                                               ->orderBy('nome', 'ASC')
                                               ->findAll();
        }
        
        $data = [
            'titulo' => ($tipo == 'receber') ? 'Contas a Receber' : 'Contas a Pagar',
            'contas' => $contas,
            'entidades' => $entidades,
            'tipo' => $tipo,
            'totalPendente' => $totais['pendente'],
            'totalConcluido' => $totais['concluido'],
            'totalAtrasado' => $totais['atrasado'],
            'totalGeral' => $totais['total']
        ];
        
        return view('contas/index', $data);
    }
    
    public function novo()
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        $usuario_id = session()->get('usuario_id');
        $empresa_id = session()->get('empresa_id');
        
        $tipo = $this->request->getGet('tipo') ?: 'receber';
        
        // Buscar categorias conforme o tipo
        $tipo_categoria = ($tipo == 'receber') ? 'receita' : 'despesa';
        $categorias = $this->categoriaModel->where('tipo', $tipo_categoria)
                                           ->where('usuario_id', $usuario_id)
                                           ->findAll();
        
        // Buscar clientes ou fornecedores
        if ($tipo == 'receber') {
            $entidades = $this->clienteModel->where('usuario_id', $usuario_id)
                                           ->where('empresa_id', $empresa_id)
                                           ->orderBy('nome', 'ASC')
                                           ->findAll();
        } else {
            $entidades = $this->fornecedorModel->where('usuario_id', $usuario_id)
                                               ->where('empresa_id', $empresa_id)
                                               ->orderBy('nome', 'ASC')
                                               ->findAll();
        }
        
        $data = [
            'titulo' => ($tipo == 'receber') ? 'Nova Conta a Receber' : 'Nova Conta a Pagar',
            'tipo' => $tipo,
            'categorias' => $categorias,
            'entidades' => $entidades
        ];
        
        return view('contas/form', $data);
    }
    
    public function editar($id = null)
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        $usuario_id = session()->get('usuario_id');
        $empresa_id = session()->get('empresa_id');
        
        if (!$id) {
            return redirect()->to('/contas');
        }
        
        $conta = $this->contaModel->find($id);
        
        // Verificar se a conta pertence ao usuário
        if ($conta['usuario_id'] != $usuario_id || $conta['empresa_id'] != $empresa_id) {
            return redirect()->to('/contas');
        }
        
        $tipo = $conta['tipo'];
        
        // Buscar categorias conforme o tipo
        $tipo_categoria = ($tipo == 'receber') ? 'receita' : 'despesa';
        $categorias = $this->categoriaModel->where('tipo', $tipo_categoria)
                                           ->where('usuario_id', $usuario_id)
                                           ->findAll();
        
        // Buscar clientes ou fornecedores
        if ($tipo == 'receber') {
            $entidades = $this->clienteModel->where('usuario_id', $usuario_id)
                                           ->where('empresa_id', $empresa_id)
                                           ->orderBy('nome', 'ASC')
                                           ->findAll();
        } else {
            $entidades = $this->fornecedorModel->where('usuario_id', $usuario_id)
                                               ->where('empresa_id', $empresa_id)
                                               ->orderBy('nome', 'ASC')
                                               ->findAll();
        }
        
        $data = [
            'titulo' => ($tipo == 'receber') ? 'Editar Conta a Receber' : 'Editar Conta a Pagar',
            'conta' => $conta,
            'tipo' => $tipo,
            'categorias' => $categorias,
            'entidades' => $entidades
        ];
        
        return view('contas/form', $data);
    }
    
    public function salvar()
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        $usuario_id = session()->get('usuario_id');
        $empresa_id = session()->get('empresa_id');
        
        $id = $this->request->getPost('id');
        $tipo = $this->request->getPost('tipo');
        
        $data = [
            'tipo' => $tipo,
            'descricao' => $this->request->getPost('descricao'),
            'valor' => $this->request->getPost('valor'),
            'data_emissao' => $this->request->getPost('data_emissao'),
            'data_vencimento' => $this->request->getPost('data_vencimento'),
            'status' => $this->request->getPost('status'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'observacoes' => $this->request->getPost('observacoes'),
            'usuario_id' => $usuario_id,
            'empresa_id' => $empresa_id
        ];
        
        // Adicionar cliente ou fornecedor conforme o tipo
        if ($tipo == 'receber') {
            $data['cliente_id'] = $this->request->getPost('entidade_id');
            $data['fornecedor_id'] = null;
        } else {
            $data['fornecedor_id'] = $this->request->getPost('entidade_id');
            $data['cliente_id'] = null;
        }
        
        // Se for marcado como concluído e não tiver data de pagamento, setar a data atual
        if ($data['status'] == 'concluido' && empty($this->request->getPost('data_pagamento'))) {
            $data['data_pagamento'] = date('Y-m-d');
        } else if ($data['status'] == 'concluido') {
            $data['data_pagamento'] = $this->request->getPost('data_pagamento');
        }
        
        if ($id) {
            // Verificar se a conta pertence ao usuário
            $conta = $this->contaModel->find($id);
            if ($conta['usuario_id'] != $usuario_id || $conta['empresa_id'] != $empresa_id) {
                return redirect()->to('/contas');
            }
            
            // Checar se está sendo marcada como concluída agora
            $statusAntigo = $conta['status'];
            $novoStatus = $data['status'];
            
            $this->contaModel->update($id, $data);
            
            // Se mudou de pendente/atrasado para concluído, criar transação
            if (($statusAntigo == 'pendente' || $statusAntigo == 'atrasado') && $novoStatus == 'concluido') {
                $this->criarTransacao($id);
            }
            
            session()->setFlashdata('mensagem', 'Conta atualizada com sucesso!');
        } else {
            $this->contaModel->insert($data);
            
            // Se já estiver marcada como concluída, criar transação
            if ($data['status'] == 'concluido') {
                $id = $this->contaModel->getInsertID();
                $this->criarTransacao($id);
            }
            
            session()->setFlashdata('mensagem', 'Conta cadastrada com sucesso!');
        }
        
        return redirect()->to('/contas?tipo=' . $tipo);
    }
    
    public function baixar($id = null)
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        $usuario_id = session()->get('usuario_id');
        $empresa_id = session()->get('empresa_id');
        
        if (!$id) {
            return redirect()->to('/contas');
        }
        
        $conta = $this->contaModel->find($id);
        
        // Verificar se a conta pertence ao usuário
        if ($conta['usuario_id'] != $usuario_id || $conta['empresa_id'] != $empresa_id) {
            return redirect()->to('/contas');
        }
        
        // Atualizar status e data de pagamento
        $this->contaModel->update($id, [
            'status' => 'concluido',
            'data_pagamento' => date('Y-m-d')
        ]);
        
        // Criar transação
        $this->criarTransacao($id);
        
        $tipo = $conta['tipo'];
        $mensagem = ($tipo == 'receber') ? 'Conta recebida com sucesso!' : 'Conta paga com sucesso!';
        
        session()->setFlashdata('mensagem', $mensagem);
        
        return redirect()->to('/contas?tipo=' . $tipo);
    }
    
    protected function criarTransacao($conta_id)
    {
        $conta = $this->contaModel->find($conta_id);
        
        // Verificar se já existe uma transação para esta conta
        $transacaoExistente = $this->transacaoModel->where('fonte_id', $conta_id)
                                                 ->where('fonte_tipo', 'conta')
                                                 ->first();
        
        if ($transacaoExistente) {
            return; // Já existe transação, não criar outra
        }
        
        $tipo_transacao = ($conta['tipo'] == 'receber') ? 'receita' : 'despesa';
        
        $dataTransacao = [
            'descricao' => $conta['descricao'],
            'valor' => $conta['valor'],
            'data' => $conta['data_pagamento'] ?? date('Y-m-d'),
            'tipo' => $tipo_transacao,
            'categoria_id' => $conta['categoria_id'],
            'usuario_id' => $conta['usuario_id'],
            'empresa_id' => $conta['empresa_id'],
            'fonte_id' => $conta_id,
            'fonte_tipo' => 'conta',
            'observacoes' => 'Gerado automaticamente a partir de ' . 
                            (($conta['tipo'] == 'receber') ? 'Contas a Receber' : 'Contas a Pagar')
        ];
        
        $this->transacaoModel->insert($dataTransacao);
    }
    
    public function excluir($id = null)
    {
        // Verificar se usuário está logado
        if (!session()->get('logado')) {
            return redirect()->to('/login');
        }
        
        $usuario_id = session()->get('usuario_id');
        $empresa_id = session()->get('empresa_id');
        
        if (!$id) {
            return redirect()->to('/contas');
        }
        
        $conta = $this->contaModel->find($id);
        
        // Verificar se a conta pertence ao usuário
        if ($conta['usuario_id'] != $usuario_id || $conta['empresa_id'] != $empresa_id) {
            return redirect()->to('/contas');
        }
        
        $tipo = $conta['tipo'];
        
        // Excluir a transação relacionada a esta conta, se existir
        $this->transacaoModel->where('fonte_id', $id)
                           ->where('fonte_tipo', 'conta')
                           ->delete();
        
        // Excluir a conta
        $this->contaModel->delete($id);
        
        session()->setFlashdata('mensagem', 'Conta excluída com sucesso!');
        
        return redirect()->to('/contas?tipo=' . $tipo);
    }
    
    private function enriquecerDadosContas($contas, $usuario_id, $empresa_id)
    {
        $resultado = [];
        
        // Carregar as categorias
        $categorias = [];
        $todas_categorias = $this->categoriaModel->where('usuario_id', $usuario_id)->findAll();
        foreach ($todas_categorias as $cat) {
            $categorias[$cat['id']] = $cat;
        }
        
        // Carregar clientes e fornecedores
        $clientes = [];
        $fornecedores = [];
        
        $todos_clientes = $this->clienteModel->where('usuario_id', $usuario_id)
                                            ->where('empresa_id', $empresa_id)
                                            ->findAll();
        
        $todos_fornecedores = $this->fornecedorModel->where('usuario_id', $usuario_id)
                                                   ->where('empresa_id', $empresa_id)
                                                   ->findAll();
        
        foreach ($todos_clientes as $cli) {
            $clientes[$cli['id']] = $cli;
        }
        
        foreach ($todos_fornecedores as $forn) {
            $fornecedores[$forn['id']] = $forn;
        }
        
        // Enriquecer cada conta com informações adicionais
        foreach ($contas as $conta) {
            // Adicionar nome da categoria
            if (!empty($conta['categoria_id']) && isset($categorias[$conta['categoria_id']])) {
                $conta['categoria_nome'] = $categorias[$conta['categoria_id']]['nome'];
            } else {
                $conta['categoria_nome'] = '';
            }
            
            // Adicionar nome do cliente ou fornecedor
            if ($conta['tipo'] == 'receber' && !empty($conta['cliente_id']) && isset($clientes[$conta['cliente_id']])) {
                $conta['entidade_nome'] = $clientes[$conta['cliente_id']]['nome'];
            } else if ($conta['tipo'] == 'pagar' && !empty($conta['fornecedor_id']) && isset($fornecedores[$conta['fornecedor_id']])) {
                $conta['entidade_nome'] = $fornecedores[$conta['fornecedor_id']]['nome'];
            } else {
                $conta['entidade_nome'] = '';
            }
            
            $resultado[] = $conta;
        }
        
        return $resultado;
    }
} 