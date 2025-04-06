<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CategoriaModel;

class CategoriaController extends ResourceController
{
    protected $categoriaModel;

    public function __construct()
    {
        $this->categoriaModel = new CategoriaModel();
    }

    public function index()
    {
        $empresaId = $this->request->getVar('empresa_id');
        $tipo = $this->request->getVar('tipo');
        
        $query = $this->categoriaModel;
        
        if ($empresaId) {
            $query = $query->porEmpresa($empresaId);
        }
        
        if ($tipo) {
            $query = $query->porTipo($tipo);
        }
        
        $categorias = $query->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $categorias
        ]);
    }

    public function show($id = null)
    {
        $categoria = $this->categoriaModel->find($id);

        if (!$categoria) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Categoria não encontrada'
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $categoria
        ]);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$this->categoriaModel->insert($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Erro ao cadastrar categoria',
                'errors' => $this->categoriaModel->errors()
            ])->setStatusCode(400);
        }

        $id = $this->categoriaModel->getInsertID();
        $categoria = $this->categoriaModel->find($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Categoria cadastrada com sucesso',
            'data' => $categoria
        ])->setStatusCode(201);
    }

    public function update($id = null)
    {
        $categoria = $this->categoriaModel->find($id);

        if (!$categoria) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Categoria não encontrada'
            ])->setStatusCode(404);
        }

        $data = $this->request->getJSON(true);

        if (!$this->categoriaModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Erro ao atualizar categoria',
                'errors' => $this->categoriaModel->errors()
            ])->setStatusCode(400);
        }

        $categoria = $this->categoriaModel->find($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Categoria atualizada com sucesso',
            'data' => $categoria
        ]);
    }

    public function delete($id = null)
    {
        $categoria = $this->categoriaModel->find($id);

        if (!$categoria) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Categoria não encontrada'
            ])->setStatusCode(404);
        }

        $this->categoriaModel->delete($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Categoria excluída com sucesso'
        ]);
    }

    // Métodos auxiliares para outros controllers
    public function buscarPorTipo($tipo, $empresaId = null)
    {
        $query = $this->categoriaModel->porTipo($tipo);
        
        if ($empresaId) {
            $query = $query->porEmpresa($empresaId);
        }
        
        return $query->findAll();
    }
    
    public function buscarIdsPorTipo($tipo, $empresaId = null)
    {
        return $this->categoriaModel->buscarIdsPorTipo($tipo, $empresaId);
    }
} 