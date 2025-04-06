<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ClienteModel;

class Cliente extends BaseController
{
    protected $clienteModel;
    
    public function __construct()
    {
        $this->clienteModel = new ClienteModel();
    }
    
    public function index()
    {
        // Verificar se usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        $usuario_id = session()->get('usuario')['id'];
        $empresa_id = session()->get('usuario')['empresa_id'];
        
        // Filtros
        $nome = $this->request->getGet('nome');
        $documento = $this->request->getGet('documento');
        
        // Aplicar filtros
        $builder = $this->clienteModel->where('usuario_id', $usuario_id)
                                     ->where('empresa_id', $empresa_id);
        
        if ($nome) {
            $builder->like('nome', $nome);
        }
        
        if ($documento) {
            $builder->like('documento', $documento);
        }
        
        $clientes = $builder->orderBy('nome', 'ASC')->findAll();
        
        $data = [
            'titulo' => 'Clientes',
            'clientes' => $clientes,
            'nome' => $nome,
            'documento' => $documento
        ];
        
        return view('clientes/index', $data);
    }
    
    public function salvar()
    {
        // Verificar se usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        $id = $this->request->getPost('id');
        
        $data = [
            'nome' => $this->request->getPost('nome'),
            'email' => $this->request->getPost('email'),
            'telefone' => $this->request->getPost('telefone'),
            'documento' => $this->request->getPost('documento'),
            'endereco' => $this->request->getPost('endereco'),
            'observacoes' => $this->request->getPost('observacoes'),
            'usuario_id' => session()->get('usuario')['id'],
            'empresa_id' => session()->get('usuario')['empresa_id']
        ];
        
        if ($id) {
            // Verificar se o cliente pertence ao usuário e empresa
            $cliente = $this->clienteModel->find($id);
            if ($cliente['usuario_id'] != session()->get('usuario')['id'] || 
                $cliente['empresa_id'] != session()->get('usuario')['empresa_id']) {
                return redirect()->to(base_url('clientes'));
            }
            
            $this->clienteModel->update($id, $data);
            session()->setFlashdata('mensagem', 'Cliente atualizado com sucesso!');
        } else {
            $this->clienteModel->insert($data);
            session()->setFlashdata('mensagem', 'Cliente cadastrado com sucesso!');
        }
        
        return redirect()->to(base_url('clientes'));
    }
    
    public function excluir($id = null)
    {
        // Verificar se usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        if (!$id) {
            return redirect()->to(base_url('clientes'));
        }
        
        $cliente = $this->clienteModel->find($id);
        
        // Verificar se o cliente pertence ao usuário e empresa
        if ($cliente['usuario_id'] != session()->get('usuario')['id'] || 
            $cliente['empresa_id'] != session()->get('usuario')['empresa_id']) {
            return redirect()->to(base_url('clientes'));
        }
        
        // Verificar se o cliente está sendo usado em alguma conta
        // TO DO: Implementar validação para evitar exclusão de clientes vinculados a contas
        
        $this->clienteModel->delete($id);
        session()->setFlashdata('mensagem', 'Cliente excluído com sucesso!');
        
        return redirect()->to(base_url('clientes'));
    }
} 