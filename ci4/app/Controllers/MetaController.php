<?php

namespace App\Controllers;

use App\Models\MetaModel;
use App\Models\CategoriaModel;
use App\Models\NotificacaoModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class MetaController extends BaseController
{
    protected $metas;
    protected $categorias;
    protected $notificacoes;
    
    public function __construct()
    {
        $this->metas = new MetaModel();
        $this->categorias = new CategoriaModel();
        $this->notificacoes = new NotificacaoModel();
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
        
        // Obter todas as metas do usuário
        $metas = $this->metas->getMetasUsuario($usuarioId);
        
        // Obter categorias para o seletor do modal
        $categorias = $this->categorias->where('empresa_id', $empresaId)
                                      ->findAll();
        
        // Calcular o progresso para cada meta
        foreach ($metas as &$meta) {
            $meta['progresso'] = $this->metas->calcularProgresso($meta['id']);
        }
        
        // Separar metas por status
        $metasEmAndamento = array_filter($metas, function($meta) {
            return $meta['status'] === 'em_andamento';
        });
        
        $metasConcluidas = array_filter($metas, function($meta) {
            return $meta['status'] === 'concluida';
        });
        
        // Preparar dados para a view
        $data = [
            'title' => 'Minhas Metas Financeiras',
            'metas' => $metas,
            'metas_em_andamento' => $metasEmAndamento,
            'metas_concluidas' => $metasConcluidas,
            'categorias' => $categorias
        ];
        
        // Renderizar a view
        return view('metas/index', $data);
    }
    
    public function nova()
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('usuario')['id'];
        
        // Obter o ID da empresa do usuário
        $empresaId = session()->get('usuario')['empresa_id'];
        
        // Obter categorias para o seletor
        $categorias = $this->categorias->where('empresa_id', $empresaId)
                                      ->findAll();
        
        // Validar dados do formulário se for um POST
        if ($this->request->getMethod() === 'post') {
            // Log para depuração
            log_message('debug', 'Dados recebidos em nova meta: ' . json_encode($this->request->getPost()));
            
            $rules = [
                'titulo' => 'required|min_length[3]|max_length[100]',
                'valor_alvo' => 'required|numeric|greater_than[0]',
                'data_alvo' => 'required|valid_date',
                'categoria_id' => 'permit_empty|numeric',
                'valor_atual' => 'permit_empty|numeric|greater_than_equal_to[0]',
                'descricao' => 'permit_empty|max_length[255]',
                'icone' => 'permit_empty|max_length[50]',
                'cor' => 'permit_empty|max_length[20]'
            ];
            
            $errors = [
                'titulo' => [
                    'required' => 'O título da meta é obrigatório',
                    'min_length' => 'O título deve ter pelo menos 3 caracteres',
                    'max_length' => 'O título não pode ter mais de 100 caracteres'
                ],
                'valor_alvo' => [
                    'required' => 'O valor alvo é obrigatório',
                    'numeric' => 'O valor alvo deve ser um número',
                    'greater_than' => 'O valor alvo deve ser maior que zero'
                ],
                'data_alvo' => [
                    'required' => 'A data alvo é obrigatória',
                    'valid_date' => 'A data alvo deve ser uma data válida'
                ],
                'valor_atual' => [
                    'numeric' => 'O valor atual deve ser um número',
                    'greater_than_equal_to' => 'O valor atual não pode ser negativo'
                ]
            ];
            
            // Validar os dados
            if ($this->validate($rules, $errors)) {
                // Preparar dados para inserção
                $dados = [
                    'usuario_id' => $usuarioId,
                    'empresa_id' => session()->get('usuario')['empresa_id'] ?? 1,
                    'titulo' => $this->request->getPost('titulo'),
                    'descricao' => $this->request->getPost('descricao'),
                    'valor_alvo' => $this->request->getPost('valor_alvo'),
                    'valor_atual' => $this->request->getPost('valor_atual') ?: 0,
                    'data_inicio' => date('Y-m-d'),
                    'data_alvo' => $this->request->getPost('data_alvo'),
                    'categoria_id' => $this->request->getPost('categoria_id'),
                    'icone' => $this->request->getPost('icone'),
                    'cor' => $this->request->getPost('cor'),
                    'status' => 'em_andamento'
                ];
                
                // Inserir a meta
                if ($this->metas->insert($dados)) {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Meta criada com sucesso!'
                        ]);
                    } else {
                        session()->setFlashdata('success', 'Meta criada com sucesso!');
                        return redirect()->to('/metas');
                    }
                } else {
                    $errorMessage = implode('<br>', $this->metas->errors());
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Erro ao criar a meta: ' . $errorMessage
                        ]);
                    } else {
                        session()->setFlashdata('error', 'Erro ao criar a meta: ' . $errorMessage);
                    }
                }
            } else {
                // Exibir erros de validação
                $errorMessage = $this->validator->listErrors();
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMessage
                    ]);
                } else {
                    session()->setFlashdata('error', $errorMessage);
                }
            }
        }
        
        // Preparar dados para a view (em caso de acesso direto, não AJAX)
        $data = [
            'title' => 'Nova Meta Financeira',
            'categorias' => $categorias,
            'validation' => $this->validator ?? null
        ];
        
        // Renderizar a view
        return view('metas/index', $data);
    }
    
    public function editar($id = null)
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('usuario')['id'];
        
        // Obter o ID da empresa do usuário
        $empresaId = session()->get('usuario')['empresa_id'];
        
        // Verificar se o ID foi fornecido
        if ($id === null) {
            throw new PageNotFoundException('Meta não encontrada');
        }
        
        // Buscar a meta pelo ID
        $meta = $this->metas->find($id);
        
        // Verificar se a meta existe e pertence ao usuário
        if (!$meta || $meta['usuario_id'] != $usuarioId) {
            throw new PageNotFoundException('Meta não encontrada');
        }
        
        // Obter categorias para o seletor
        $categorias = $this->categorias->where('empresa_id', $empresaId)
                                      ->findAll();
        
        // Validar dados do formulário se for um POST
        if ($this->request->getMethod() === 'post') {
            // Log para depuração
            log_message('debug', 'Dados recebidos na edição da meta ' . $id . ': ' . json_encode($this->request->getPost()));
            
            $rules = [
                'titulo' => 'required|min_length[3]|max_length[100]',
                'valor_alvo' => 'required|numeric|greater_than[0]',
                'data_alvo' => 'required|valid_date',
                'categoria_id' => 'permit_empty|numeric',
                'valor_atual' => 'permit_empty|numeric|greater_than_equal_to[0]',
                'descricao' => 'permit_empty|max_length[255]',
                'icone' => 'permit_empty|max_length[50]',
                'cor' => 'permit_empty|max_length[20]',
                'status' => 'permit_empty|in_list[em_andamento,concluida,cancelada]'
            ];
            
            $errors = [
                'titulo' => [
                    'required' => 'O título da meta é obrigatório',
                    'min_length' => 'O título deve ter pelo menos 3 caracteres',
                    'max_length' => 'O título não pode ter mais de 100 caracteres'
                ],
                'valor_alvo' => [
                    'required' => 'O valor alvo é obrigatório',
                    'numeric' => 'O valor alvo deve ser um número',
                    'greater_than' => 'O valor alvo deve ser maior que zero'
                ],
                'data_alvo' => [
                    'required' => 'A data alvo é obrigatória',
                    'valid_date' => 'A data alvo deve ser uma data válida'
                ],
                'valor_atual' => [
                    'numeric' => 'O valor atual deve ser um número',
                    'greater_than_equal_to' => 'O valor atual não pode ser negativo'
                ],
                'status' => [
                    'in_list' => 'O status deve ser um dos valores aceitos'
                ]
            ];
            
            // Validar os dados
            if ($this->validate($rules, $errors)) {
                // Preparar dados para atualização
                $dados = [
                    'titulo' => $this->request->getPost('titulo'),
                    'descricao' => $this->request->getPost('descricao'),
                    'valor_alvo' => $this->request->getPost('valor_alvo'),
                    'valor_atual' => $this->request->getPost('valor_atual'),
                    'data_alvo' => $this->request->getPost('data_alvo'),
                    'categoria_id' => $this->request->getPost('categoria_id'),
                    'icone' => $this->request->getPost('icone'),
                    'cor' => $this->request->getPost('cor')
                ];
                
                // Incluir status apenas se for enviado
                if ($this->request->getPost('status')) {
                    $dados['status'] = $this->request->getPost('status');
                }
                
                // Atualizar a meta
                if ($this->metas->update($id, $dados)) {
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => true,
                            'message' => 'Meta atualizada com sucesso!'
                        ]);
                    } else {
                        session()->setFlashdata('success', 'Meta atualizada com sucesso!');
                        return redirect()->to('/metas');
                    }
                } else {
                    $errorMessage = implode('<br>', $this->metas->errors());
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON([
                            'success' => false,
                            'message' => 'Erro ao atualizar a meta: ' . $errorMessage
                        ]);
                    } else {
                        session()->setFlashdata('error', 'Erro ao atualizar a meta: ' . $errorMessage);
                    }
                }
            } else {
                // Exibir erros de validação
                $errorMessage = $this->validator->listErrors();
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => $errorMessage
                    ]);
                } else {
                    session()->setFlashdata('error', $errorMessage);
                }
            }
        }
        
        // Preparar dados para a view
        $data = [
            'title' => 'Editar Meta Financeira',
            'meta' => $meta,
            'categorias' => $categorias,
            'validation' => $this->validator ?? null
        ];
        
        // Renderizar a view
        return view('metas/index', $data);
    }
    
    public function detalhes($id = null)
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('usuario')['id'];
        
        // Verificar se o ID foi fornecido
        if ($id === null) {
            throw new PageNotFoundException('Meta não encontrada');
        }
        
        // Buscar a meta pelo ID
        $meta = $this->metas->find($id);
        
        // Verificar se a meta existe e pertence ao usuário
        if (!$meta || $meta['usuario_id'] != $usuarioId) {
            throw new PageNotFoundException('Meta não encontrada');
        }
        
        // Calcular o progresso
        $meta['progresso'] = $this->metas->calcularProgresso($id);
        
        // Buscar categoria se existir
        if ($meta['categoria_id']) {
            $categoria = $this->categorias->find($meta['categoria_id']);
            $meta['categoria_nome'] = $categoria ? $categoria['nome'] : 'Categoria removida';
        } else {
            $meta['categoria_nome'] = 'Sem categoria';
        }
        
        // Verificar se é uma requisição AJAX
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'meta' => $meta
            ]);
        }
        
        // Preparar dados para a view (caso não seja AJAX)
        $data = [
            'title' => 'Detalhes da Meta',
            'meta' => $meta
        ];
        
        // Renderizar a view
        return view('metas/detalhes', $data);
    }
    
    public function atualizar($id = null)
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('usuario')['id'];
        
        // Verificar se é uma requisição AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->to('/metas');
        }
        
        // Verificar se o ID foi fornecido
        if ($id === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID da meta não informado'
            ]);
        }
        
        // Buscar a meta pelo ID
        $meta = $this->metas->find($id);
        
        // Verificar se a meta existe e pertence ao usuário
        if (!$meta || $meta['usuario_id'] != $usuarioId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Meta não encontrada'
            ]);
        }
        
        // Obter novo valor atual
        $valorAtual = $this->request->getPost('valor_atual');
        
        if ($valorAtual === null) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Valor atual não informado'
            ]);
        }
        
        // Atualizar o valor atual
        $dados = ['valor_atual' => $valorAtual];
        
        // Verificar se atingiu a meta
        if ($valorAtual >= $meta['valor_alvo'] && $meta['status'] !== 'concluida') {
            $dados['status'] = 'concluida';
            
            // Criar notificação de meta alcançada
            $this->notificacoes->notificarMetaAlcancada($meta);
        }
        
        // Atualizar a meta
        if ($this->metas->update($id, $dados)) {
            // Recalcular o progresso
            $progresso = $this->metas->calcularProgresso($id);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Valor atualizado com sucesso',
                'progresso' => $progresso,
                'status' => $dados['status'] ?? $meta['status'],
                'valor_atual' => $valorAtual
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao atualizar valor: ' . implode(', ', $this->metas->errors())
            ]);
        }
    }
    
    public function excluir($id = null)
    {
        // Verificar se o usuário está logado
        if (!session()->get('usuario')) {
            return redirect()->to(base_url('login'));
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('usuario')['id'];
        
        // Verificar se o ID foi fornecido
        if ($id === null) {
            throw new PageNotFoundException('Meta não encontrada');
        }
        
        // Buscar a meta pelo ID
        $meta = $this->metas->find($id);
        
        // Verificar se a meta existe e pertence ao usuário
        if (!$meta || $meta['usuario_id'] != $usuarioId) {
            throw new PageNotFoundException('Meta não encontrada');
        }
        
        // Excluir a meta (soft delete)
        if ($this->metas->delete($id)) {
            session()->setFlashdata('success', 'Meta excluída com sucesso!');
        } else {
            session()->setFlashdata('error', 'Erro ao excluir a meta.');
        }
        
        // Redirecionar para a lista de metas
        return redirect()->to('/metas');
    }
} 