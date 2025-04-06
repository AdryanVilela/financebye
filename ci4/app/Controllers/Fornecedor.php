<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\FornecedorModel;

class Fornecedor extends BaseController
{
    protected $fornecedorModel;
    
    public function __construct()
    {
        $this->fornecedorModel = new FornecedorModel();
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
        $builder = $this->fornecedorModel->where('usuario_id', $usuario_id)
                                         ->where('empresa_id', $empresa_id);
        
        if ($nome) {
            $builder->like('nome', $nome);
        }
        
        if ($documento) {
            $builder->like('documento', $documento);
        }
        
        $fornecedores = $builder->orderBy('nome', 'ASC')->findAll();
        
        $data = [
            'titulo' => 'Fornecedores',
            'fornecedores' => $fornecedores,
            'nome' => $nome,
            'documento' => $documento
        ];
        
        return view('fornecedores/index', $data);
    }
    
    public function obter($id = null)
    {
        // Verificar se usuário está logado
        if (!session()->get('usuario')) {
            return $this->response->setJSON(['error' => 'Usuário não autenticado']);
        }
        
        if (!$id) {
            return $this->response->setJSON(['error' => 'ID não fornecido']);
        }
        
        $fornecedor = $this->fornecedorModel->find($id);
        
        if (!$fornecedor) {
            return $this->response->setJSON(['error' => 'Fornecedor não encontrado']);
        }
        
        // Verificar se o fornecedor pertence ao usuário e empresa
        if ($fornecedor['usuario_id'] != session()->get('usuario')['id'] || 
            $fornecedor['empresa_id'] != session()->get('usuario')['empresa_id']) {
            return $this->response->setJSON(['error' => 'Acesso negado']);
        }
        
        return $this->response->setJSON($fornecedor);
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
            // Verificar se o fornecedor pertence ao usuário e empresa
            $fornecedor = $this->fornecedorModel->find($id);
            if ($fornecedor['usuario_id'] != session()->get('usuario')['id'] || 
                $fornecedor['empresa_id'] != session()->get('usuario')['empresa_id']) {
                return redirect()->to(base_url('fornecedores'));
            }
            
            $this->fornecedorModel->update($id, $data);
            session()->setFlashdata('mensagem', 'Fornecedor atualizado com sucesso!');
        } else {
            $this->fornecedorModel->insert($data);
            session()->setFlashdata('mensagem', 'Fornecedor cadastrado com sucesso!');
        }
        
        return redirect()->to(base_url('fornecedores'));
    }
    
    public function excluir($id = null)
    {
        // Verificar se usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        if (!$id) {
            return redirect()->to(base_url('fornecedores'));
        }
        
        $fornecedor = $this->fornecedorModel->find($id);
        
        // Verificar se o fornecedor pertence ao usuário e empresa
        if ($fornecedor['usuario_id'] != session()->get('usuario')['id'] || 
            $fornecedor['empresa_id'] != session()->get('usuario')['empresa_id']) {
            return redirect()->to(base_url('fornecedores'));
        }
        
        // Verificar se o fornecedor está sendo usado em alguma conta
        // TO DO: Implementar validação para evitar exclusão de fornecedores vinculados a contas
        
        $this->fornecedorModel->delete($id);
        session()->setFlashdata('mensagem', 'Fornecedor excluído com sucesso!');
        
        return redirect()->to(base_url('fornecedores'));
    }
} 