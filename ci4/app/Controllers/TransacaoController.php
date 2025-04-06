<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\TransacaoModel;
use App\Controllers\CategoriaController;

class TransacaoController extends ResourceController
{
    protected $transacaoModel;
    protected $categoriaController;

    public function __construct()
    {
        $this->transacaoModel = new TransacaoModel();
        $this->categoriaController = new CategoriaController();
    }

    public function index()
    {
        $empresaId = $this->request->getVar('empresa_id');
        $tipo = $this->request->getVar('tipo');
        $dataInicio = $this->request->getVar('data_inicio');
        $dataFim = $this->request->getVar('data_fim');
        
        $query = $this->transacaoModel->comCategoria();
        
        if ($empresaId) {
            $query = $query->porEmpresa($empresaId);
        }
        
        if ($tipo) {
            $categoriasIds = $this->categoriaController->buscarIdsPorTipo($tipo, $empresaId);
            if (!empty($categoriasIds)) {
                $query = $query->whereIn('transacoes.categoria_id', $categoriasIds);
            }
        }
        
        if ($dataInicio) {
            $query = $query->where('transacoes.data >=', $dataInicio);
        }
        
        if ($dataFim) {
            $query = $query->where('transacoes.data <=', $dataFim);
        }
        
        $transacoes = $query->orderBy('transacoes.data', 'DESC')->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $transacoes
        ]);
    }

    public function show($id = null)
    {
        $transacao = $this->transacaoModel->comCategoria()->find($id);

        if (!$transacao) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Transação não encontrada'
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $transacao
        ]);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);
        
        // Obtém a sessão do usuário logado
        $session = session();
        $usuario = $session->get('usuario');
        
        if ($usuario) {
            $data['usuario_id'] = $usuario['id'];
            $data['empresa_id'] = $usuario['empresa_id'];
        }

        if (!$this->transacaoModel->insert($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Erro ao cadastrar transação',
                'errors' => $this->transacaoModel->errors()
            ])->setStatusCode(400);
        }

        $id = $this->transacaoModel->getInsertID();
        $transacao = $this->transacaoModel->comCategoria()->find($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Transação cadastrada com sucesso',
            'data' => $transacao
        ])->setStatusCode(201);
    }

    public function update($id = null)
    {
        $transacao = $this->transacaoModel->find($id);

        if (!$transacao) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Transação não encontrada'
            ])->setStatusCode(404);
        }

        $data = $this->request->getJSON(true);

        if (!$this->transacaoModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Erro ao atualizar transação',
                'errors' => $this->transacaoModel->errors()
            ])->setStatusCode(400);
        }

        $transacao = $this->transacaoModel->comCategoria()->find($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Transação atualizada com sucesso',
            'data' => $transacao
        ]);
    }

    public function delete($id = null)
    {
        $transacao = $this->transacaoModel->find($id);

        if (!$transacao) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Transação não encontrada'
            ])->setStatusCode(404);
        }

        $this->transacaoModel->delete($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Transação excluída com sucesso'
        ]);
    }

    public function resumo()
    {
        $empresaId = $this->request->getVar('empresa_id');
        $dataInicio = $this->request->getVar('data_inicio');
        $dataFim = $this->request->getVar('data_fim');
        
        // Obtém a sessão do usuário logado se empresa_id não for fornecido
        if (!$empresaId) {
            $session = session();
            $usuario = $session->get('usuario');
            
            if ($usuario) {
                $empresaId = $usuario['empresa_id'];
            }
        }
        
        if (!$empresaId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Empresa não especificada'
            ])->setStatusCode(400);
        }
        
        $periodo = [];
        if ($dataInicio) {
            $periodo['inicio'] = $dataInicio;
        }
        if ($dataFim) {
            $periodo['fim'] = $dataFim;
        }
        
        $totalReceitas = $this->transacaoModel->totalReceitas($empresaId, $periodo);
        $totalDespesas = $this->transacaoModel->totalDespesas($empresaId, $periodo);
        $saldo = $this->transacaoModel->saldo($empresaId, $periodo);
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => [
                'receitas' => $totalReceitas,
                'despesas' => $totalDespesas,
                'saldo' => $saldo
            ]
        ]);
    }

    public function listarDespesas()
    {
        $empresaId = $this->request->getVar('empresa_id');
        
        // Obtém a sessão do usuário logado se empresa_id não for fornecido
        if (!$empresaId) {
            $session = session();
            $usuario = $session->get('usuario');
            
            if ($usuario) {
                $empresaId = $usuario['empresa_id'];
            }
        }
        
        if (!$empresaId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Empresa não especificada'
            ])->setStatusCode(400);
        }
        
        // Usa o CategoriaController para obter os IDs de categorias do tipo despesa
        $idsDespesas = $this->categoriaController->buscarIdsPorTipo('despesa', $empresaId);
        
        $transacoes = $this->transacaoModel->comCategoria()
                                           ->whereIn('transacoes.categoria_id', $idsDespesas)
                                           ->where('transacoes.empresa_id', $empresaId)
                                           ->orderBy('transacoes.data', 'DESC')
                                           ->findAll();
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $transacoes
        ]);
    }

    public function listarReceitas()
    {
        $empresaId = $this->request->getVar('empresa_id');
        
        // Obtém a sessão do usuário logado se empresa_id não for fornecido
        if (!$empresaId) {
            $session = session();
            $usuario = $session->get('usuario');
            
            if ($usuario) {
                $empresaId = $usuario['empresa_id'];
            }
        }
        
        if (!$empresaId) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Empresa não especificada'
            ])->setStatusCode(400);
        }
        
        // Usa o CategoriaController para obter os IDs de categorias do tipo receita
        $idsReceitas = $this->categoriaController->buscarIdsPorTipo('receita', $empresaId);
        
        $transacoes = $this->transacaoModel->comCategoria()
                                           ->whereIn('transacoes.categoria_id', $idsReceitas)
                                           ->where('transacoes.empresa_id', $empresaId)
                                           ->orderBy('transacoes.data', 'DESC')
                                           ->findAll();
        
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $transacoes
        ]);
    }
} 