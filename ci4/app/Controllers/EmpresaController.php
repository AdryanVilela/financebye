<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\EmpresaModel;

class EmpresaController extends ResourceController
{
    protected $empresaModel;

    public function __construct()
    {
        $this->empresaModel = new EmpresaModel();
    }

    public function index()
    {
        $empresas = $this->empresaModel->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $empresas
        ]);
    }

    public function show($id = null)
    {
        $empresa = $this->empresaModel->find($id);

        if (!$empresa) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Empresa não encontrada'
            ])->setStatusCode(404);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $empresa
        ]);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$this->empresaModel->insert($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Erro ao cadastrar empresa',
                'errors' => $this->empresaModel->errors()
            ])->setStatusCode(400);
        }

        $id = $this->empresaModel->getInsertID();
        $empresa = $this->empresaModel->find($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Empresa cadastrada com sucesso',
            'data' => $empresa
        ])->setStatusCode(201);
    }

    public function update($id = null)
    {
        $empresa = $this->empresaModel->find($id);

        if (!$empresa) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Empresa não encontrada'
            ])->setStatusCode(404);
        }

        $data = $this->request->getJSON(true);

        if (!$this->empresaModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Erro ao atualizar empresa',
                'errors' => $this->empresaModel->errors()
            ])->setStatusCode(400);
        }

        $empresa = $this->empresaModel->find($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Empresa atualizada com sucesso',
            'data' => $empresa
        ]);
    }

    public function delete($id = null)
    {
        $empresa = $this->empresaModel->find($id);

        if (!$empresa) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Empresa não encontrada'
            ])->setStatusCode(404);
        }

        $this->empresaModel->delete($id);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Empresa excluída com sucesso'
        ]);
    }

    // Método auxiliar para outros controllers que precisem buscar empresas
    public function buscarPorId($id)
    {
        return $this->empresaModel->find($id);
    }
} 