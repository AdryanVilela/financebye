<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificacaoModel extends Model
{
    protected $table            = 'notificacoes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'usuario_id', 'tipo', 'titulo', 'mensagem', 'referencia_id', 'lida', 'data_criacao'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'data_criacao';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'usuario_id' => 'required|numeric',
        'tipo'       => 'required|alpha_dash',
        'titulo'     => 'required|min_length[3]|max_length[100]',
        'mensagem'   => 'required',
    ];
    
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'O ID do usuário é obrigatório',
            'numeric'  => 'O ID do usuário deve ser numérico'
        ],
        'tipo' => [
            'required'   => 'O tipo de notificação é obrigatório',
            'alpha_dash' => 'O tipo deve conter apenas letras, números, hífens e sublinhados'
        ],
        'titulo' => [
            'required'    => 'O título é obrigatório',
            'min_length'  => 'O título deve ter pelo menos {param} caracteres',
            'max_length'  => 'O título não pode exceder {param} caracteres'
        ],
        'mensagem' => [
            'required' => 'A mensagem é obrigatória'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setDefaults'];
    
    protected function setDefaults(array $data)
    {
        // Se não definiu status de leitura, definir como não lida
        if (!isset($data['data']['lida'])) {
            $data['data']['lida'] = 0;
        }
        
        // Se não definiu data, usar a data atual
        if (!isset($data['data']['data'])) {
            $data['data']['data'] = date('Y-m-d H:i:s');
        }
        
        // Se não definiu prioridade, definir como normal
        if (!isset($data['data']['prioridade'])) {
            $data['data']['prioridade'] = 'normal';
        }
        
        return $data;
    }
    
    /**
     * Criar uma notificação para o usuário
     */
    public function criarNotificacao($usuarioId, $tipo, $titulo, $mensagem, $referenciaId = null)
    {
        // Verificar se já existe a tabela de notificações
        if (!$this->db->tableExists('notificacoes')) {
            $this->criarTabelaNotificacoes();
        }

        // Dados da notificação
        $dados = [
            'usuario_id' => $usuarioId,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'mensagem' => $mensagem,
            'referencia_id' => $referenciaId,
            'lida' => 0,
            'data_criacao' => date('Y-m-d H:i:s')
        ];
        
        // Inserir notificação
        return $this->insert($dados);
    }
    
    /**
     * Notificar quando uma meta é alcançada
     */
    public function notificarMetaAlcancada($meta)
    {
        // Dados da notificação
        $titulo = 'Meta Alcançada!';
        $mensagem = 'Parabéns! Você atingiu sua meta de "' . $meta['titulo'] . '".';
        
        // Criar notificação
        return $this->criarNotificacao(
            $meta['usuario_id'],
            'meta_alcancada',
            $titulo,
            $mensagem,
            $meta['id']
        );
    }
    
    /**
     * Obter notificações não lidas de um usuário
     */
    public function getNotificacoesNaoLidas($usuarioId)
    {
        // Verificar se já existe a tabela de notificações
        if (!$this->db->tableExists('notificacoes')) {
            return [];
        }
        
        return $this->where('usuario_id', $usuarioId)
                    ->where('lida', 0)
                    ->orderBy('data_criacao', 'DESC')
                    ->findAll();
    }
    
    /**
     * Marcar notificação como lida
     */
    public function marcarComoLida($notificacaoId)
    {
        return $this->update($notificacaoId, ['lida' => 1]);
    }
    
    /**
     * Marcar todas as notificações de um usuário como lidas
     */
    public function marcarTodasComoLidas($usuarioId)
    {
        return $this->where('usuario_id', $usuarioId)
                    ->where('lida', 0)
                    ->set(['lida' => 1])
                    ->update();
    }
    
    /**
     * Criar tabela de notificações se não existir
     */
    private function criarTabelaNotificacoes()
    {
        // Definição da tabela
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'tipo' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'titulo' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'mensagem' => [
                'type' => 'TEXT'
            ],
            'referencia_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'lida' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'data_criacao' => [
                'type' => 'DATETIME'
            ]
        ];
        
        // Chave primária
        $this->forge = \Config\Database::forge();
        $this->forge->addField($fields);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('notificacoes', true);
    }
    
    // Criar notificação de orçamento excedido
    public function notificarOrcamentoExcedido($orcamento, $percentual)
    {
        // Verificar se já existe notificação para este orçamento neste mês
        $existente = $this->where('usuario_id', $orcamento['usuario_id'])
                          ->where('tipo', 'orcamento_excedido')
                          ->where('item_id', $orcamento['id'])
                          ->where('MONTH(data)', date('m'))
                          ->where('YEAR(data)', date('Y'))
                          ->first();
                          
        if ($existente) {
            return false; // Já existe notificação para este orçamento neste mês
        }
        
        // Buscar nome da categoria
        $categoriaModel = new \App\Models\CategoriaModel();
        $categoria = $categoriaModel->find($orcamento['categoria_id']);
        $nomeCategoria = $categoria ? $categoria['nome'] : 'Categoria';
        
        $titulo = "Orçamento excedido: {$nomeCategoria}";
        $mensagem = "Você já gastou {$percentual}% do orçamento para {$nomeCategoria} em " . 
                   date('F', mktime(0, 0, 0, $orcamento['mes'], 1, $orcamento['ano'])) . 
                   " de {$orcamento['ano']}.";
        
        $link = "/orcamentos/detalhes/{$orcamento['id']}";
        
        return $this->criarNotificacao(
            $orcamento['usuario_id'],
            'orcamento_excedido',
            $titulo,
            $mensagem,
            $link,
            $orcamento['id'],
            'alta'
        );
    }
    
    // Criar notificação de conta a vencer
    public function notificarContaVencimento($conta, $diasRestantes)
    {
        // Verificar se já existe notificação para esta conta
        $existente = $this->where('usuario_id', $conta['usuario_id'])
                          ->where('tipo', 'conta_vencimento')
                          ->where('item_id', $conta['id'])
                          ->first();
                          
        if ($existente) {
            return false; // Já existe notificação para esta conta
        }
        
        $titulo = "Conta a vencer: {$conta['descricao']}";
        $mensagem = "Você tem uma conta de R$ " . number_format($conta['valor'], 2, ',', '.') . 
                   " a " . ($conta['tipo'] == 'pagar' ? 'pagar' : 'receber') . 
                   " em {$diasRestantes} dias (vencimento: " . date('d/m/Y', strtotime($conta['data_vencimento'])) . ").";
        
        $link = "/contas/detalhes/{$conta['id']}";
        
        $prioridade = ($diasRestantes <= 3) ? 'alta' : 'normal';
        
        return $this->criarNotificacao(
            $conta['usuario_id'],
            'conta_vencimento',
            $titulo,
            $mensagem,
            $link,
            $conta['id'],
            $prioridade
        );
    }
    
    // Criar notificação de saldo baixo em carteira
    public function notificarSaldoBaixo($carteira, $limiteMinimo = 100)
    {
        // Verificar se já existe notificação para esta carteira nos últimos 7 dias
        $dataLimite = date('Y-m-d H:i:s', strtotime("-7 days"));
        $existente = $this->where('usuario_id', $carteira['usuario_id'])
                          ->where('tipo', 'saldo_baixo')
                          ->where('item_id', $carteira['id'])
                          ->where('data >', $dataLimite)
                          ->first();
                          
        if ($existente) {
            return false; // Já existe notificação recente para esta carteira
        }
        
        $titulo = "Saldo baixo: {$carteira['nome']}";
        $mensagem = "Sua carteira {$carteira['nome']} está com saldo baixo: " . 
                   "R$ " . number_format($carteira['saldo'], 2, ',', '.') . ".";
        
        $link = "/carteiras/detalhes/{$carteira['id']}";
        
        return $this->criarNotificacao(
            $carteira['usuario_id'],
            'saldo_baixo',
            $titulo,
            $mensagem,
            $link,
            $carteira['id'],
            'alta'
        );
    }
    
    // Criar notificação de relatório agendado
    public function notificarRelatorioGerado($relatorio, $usuarioId)
    {
        $titulo = "Relatório gerado: {$relatorio['nome']}";
        $mensagem = "Seu relatório agendado '{$relatorio['nome']}' foi gerado e está disponível para visualização.";
        
        $link = "/relatorios/ver/{$relatorio['id']}";
        
        return $this->criarNotificacao(
            $usuarioId,
            'relatorio_gerado',
            $titulo,
            $mensagem,
            $link,
            $relatorio['id'],
            'normal'
        );
    }
} 