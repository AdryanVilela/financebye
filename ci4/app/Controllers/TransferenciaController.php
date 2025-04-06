<?php

namespace App\Controllers;

use App\Models\TransferenciaModel;
use App\Models\CarteiraModel;
use App\Models\NotificacaoModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class TransferenciaController extends BaseController
{
    protected $transferencias;
    protected $carteiras;
    protected $notificacoes;
    
    public function __construct()
    {
        $this->transferencias = new TransferenciaModel();
        $this->carteiras = new CarteiraModel();
        $this->notificacoes = new NotificacaoModel();
    }
    
    public function index()
    {
        // Verificar se o usuário está logado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('id');
        
        // Obter carteiras ativas para a seleção
        $carteiras = $this->carteiras->getCarteirasAtivas($usuarioId);
        
        // Definir período padrão para filtro
        $hoje = date('Y-m-d');
        $primeiroDiaMes = date('Y-m-01');
        $ultimoDiaMes = date('Y-m-t');
        
        // Obter transferências do mês atual
        $transferencias = $this->transferencias->getTransferenciasPeriodo(
            $usuarioId,
            $primeiroDiaMes,
            $ultimoDiaMes
        );
        
        // Estatísticas do mês atual
        $estatisticas = $this->transferencias->getEstatisticas($usuarioId);
        
        // Preparar dados para a view
        $data = [
            'title' => 'Transferências',
            'carteiras' => $carteiras,
            'transferencias' => $transferencias,
            'estatisticas' => $estatisticas,
            'data_inicio' => $primeiroDiaMes,
            'data_fim' => $ultimoDiaMes
        ];
        
        // Carregar as views
        echo view('templates/header', $data);
        echo view('templates/sidebar');
        echo view('transferencias/index', $data);
        echo view('templates/footer');
    }
    
    public function nova()
    {
        // Verificar se o usuário está logado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('id');
        
        // Obter carteiras ativas para a seleção
        $carteiras = $this->carteiras->getCarteirasAtivas($usuarioId);
        
        // Verificar se há pelo menos duas carteiras para transferir
        if (count($carteiras) < 2) {
            session()->setFlashdata('error', 'Você precisa ter pelo menos duas carteiras ativas para fazer transferências.');
            return redirect()->to('/carteiras');
        }
        
        // Validar dados do formulário se for um POST
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'carteira_origem_id' => 'required|numeric',
                'carteira_destino_id' => 'required|numeric|differs[carteira_origem_id]',
                'valor' => 'required|numeric|greater_than[0]',
                'data' => 'required|valid_date',
                'descricao' => 'permit_empty|max_length[255]',
                'taxa' => 'permit_empty|numeric|greater_than_equal_to[0]'
            ];
            
            $errors = [
                'carteira_origem_id' => [
                    'required' => 'Selecione a carteira de origem',
                    'numeric' => 'Carteira de origem inválida'
                ],
                'carteira_destino_id' => [
                    'required' => 'Selecione a carteira de destino',
                    'numeric' => 'Carteira de destino inválida',
                    'differs' => 'A carteira de destino deve ser diferente da carteira de origem'
                ],
                'valor' => [
                    'required' => 'Informe o valor da transferência',
                    'numeric' => 'O valor deve ser um número',
                    'greater_than' => 'O valor deve ser maior que zero'
                ],
                'data' => [
                    'required' => 'Informe a data da transferência',
                    'valid_date' => 'Data inválida'
                ],
                'taxa' => [
                    'numeric' => 'A taxa deve ser um número',
                    'greater_than_equal_to' => 'A taxa não pode ser negativa'
                ]
            ];
            
            // Validar os dados
            if ($this->validate($rules, $errors)) {
                // Obter os dados validados
                $carteiraOrigemId = $this->request->getPost('carteira_origem_id');
                $carteiraDestinoId = $this->request->getPost('carteira_destino_id');
                $valor = $this->request->getPost('valor');
                $taxa = $this->request->getPost('taxa') ?: 0;
                
                // Verificar se há saldo suficiente na carteira de origem
                if ($this->transferencias->validarSaldoSuficiente($carteiraOrigemId, $valor, $taxa)) {
                    // Preparar dados para inserção
                    $dados = [
                        'usuario_id' => $usuarioId,
                        'carteira_origem_id' => $carteiraOrigemId,
                        'carteira_destino_id' => $carteiraDestinoId,
                        'valor' => $valor,
                        'taxa' => $taxa,
                        'data' => $this->request->getPost('data'),
                        'descricao' => $this->request->getPost('descricao') ?: 'Transferência entre contas'
                    ];
                    
                    // Inserir a transferência
                    if ($this->transferencias->insert($dados)) {
                        session()->setFlashdata('success', 'Transferência realizada com sucesso!');
                        return redirect()->to('/transferencias');
                    } else {
                        session()->setFlashdata('error', 'Erro ao realizar a transferência: ' . implode('<br>', $this->transferencias->errors()));
                    }
                } else {
                    session()->setFlashdata('error', 'Saldo insuficiente na carteira de origem.');
                }
            } else {
                // Exibir erros de validação
                session()->setFlashdata('error', $this->validator->listErrors());
            }
        }
        
        // Preparar dados para a view
        $data = [
            'title' => 'Nova Transferência',
            'carteiras' => $carteiras,
            'validation' => $this->validator ?? null
        ];
        
        // Carregar as views
        echo view('templates/header', $data);
        echo view('templates/sidebar');
        echo view('transferencias/formulario', $data);
        echo view('templates/footer');
    }
    
    public function detalhes($id = null)
    {
        // Verificar se o usuário está logado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('id');
        
        // Verificar se o ID foi fornecido
        if ($id === null) {
            throw new PageNotFoundException('Transferência não encontrada');
        }
        
        // Buscar a transferência pelo ID
        $transferencia = $this->transferencias->find($id);
        
        // Verificar se a transferência existe e pertence ao usuário
        if (!$transferencia || $transferencia['usuario_id'] != $usuarioId) {
            throw new PageNotFoundException('Transferência não encontrada');
        }
        
        // Buscar informações adicionais (nomes das carteiras)
        $carteiraOrigem = $this->carteiras->find($transferencia['carteira_origem_id']);
        $carteiraDestino = $this->carteiras->find($transferencia['carteira_destino_id']);
        
        $transferencia['carteira_origem_nome'] = $carteiraOrigem ? $carteiraOrigem['nome'] : 'Carteira removida';
        $transferencia['carteira_destino_nome'] = $carteiraDestino ? $carteiraDestino['nome'] : 'Carteira removida';
        
        // Preparar dados para a view
        $data = [
            'title' => 'Detalhes da Transferência',
            'transferencia' => $transferencia
        ];
        
        // Carregar as views
        echo view('templates/header', $data);
        echo view('templates/sidebar');
        echo view('transferencias/detalhes', $data);
        echo view('templates/footer');
    }
    
    public function excluir($id = null)
    {
        // Verificar se o usuário está logado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('id');
        
        // Verificar se o ID foi fornecido
        if ($id === null) {
            throw new PageNotFoundException('Transferência não encontrada');
        }
        
        // Buscar a transferência pelo ID
        $transferencia = $this->transferencias->find($id);
        
        // Verificar se a transferência existe e pertence ao usuário
        if (!$transferencia || $transferencia['usuario_id'] != $usuarioId) {
            throw new PageNotFoundException('Transferência não encontrada');
        }
        
        // Excluir a transferência (soft delete)
        if ($this->transferencias->delete($id)) {
            session()->setFlashdata('success', 'Transferência excluída com sucesso!');
        } else {
            session()->setFlashdata('error', 'Erro ao excluir a transferência.');
        }
        
        // Redirecionar para a lista de transferências
        return redirect()->to('/transferencias');
    }
    
    public function filtrar()
    {
        // Verificar se o usuário está logado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('id');
        
        // Verificar se é uma requisição AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->to('/transferencias');
        }
        
        // Obter parâmetros do filtro
        $dataInicio = $this->request->getPost('data_inicio');
        $dataFim = $this->request->getPost('data_fim');
        $carteiraId = $this->request->getPost('carteira_id');
        
        // Validar as datas
        if (!$dataInicio || !$dataFim) {
            $hoje = date('Y-m-d');
            $dataInicio = date('Y-m-01');
            $dataFim = date('Y-m-t');
        }
        
        // Buscar transferências com o filtro
        $transferencias = $this->transferencias->getTransferenciasPeriodo(
            $usuarioId,
            $dataInicio,
            $dataFim,
            $carteiraId ?: null
        );
        
        // Preparar dados para a resposta
        $data = [
            'transferencias' => $transferencias,
            'success' => true
        ];
        
        // Retornar como JSON
        return $this->response->setJSON($data);
    }
    
    public function estatisticas()
    {
        // Verificar se o usuário está logado
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }
        
        // Obter o ID do usuário logado
        $usuarioId = session()->get('id');
        
        // Verificar se é uma requisição AJAX
        if (!$this->request->isAJAX()) {
            return redirect()->to('/transferencias');
        }
        
        // Obter parâmetros do filtro
        $mes = $this->request->getPost('mes') ?: date('m');
        $ano = $this->request->getPost('ano') ?: date('Y');
        
        // Buscar estatísticas com o filtro
        $estatisticas = $this->transferencias->getEstatisticas($usuarioId, $mes, $ano);
        
        // Preparar dados para a resposta
        $data = [
            'estatisticas' => $estatisticas,
            'success' => true
        ];
        
        // Retornar como JSON
        return $this->response->setJSON($data);
    }
} 