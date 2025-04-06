<?php

namespace App\Models;

use CodeIgniter\Model;

class CarteiraModel extends Model
{
    protected $table            = 'carteiras';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'usuario_id', 'nome', 'descricao', 'saldo', 'icone', 'cor', 
        'tipo', 'instituicao', 'ativo', 'principal'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'usuario_id'  => 'required|numeric',
        'nome'        => 'required|min_length[2]|max_length[100]',
        'saldo'       => 'required|numeric',
        'tipo'        => 'required|in_list[conta_corrente,conta_poupanca,dinheiro,investimento,cartao_credito,outros]',
    ];
    
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'O ID do usuário é obrigatório',
            'numeric'  => 'O ID do usuário deve ser numérico'
        ],
        'nome' => [
            'required'    => 'O nome da carteira é obrigatório',
            'min_length'  => 'O nome deve ter pelo menos {param} caracteres',
            'max_length'  => 'O nome não pode ter mais de {param} caracteres'
        ],
        'saldo' => [
            'required' => 'O saldo é obrigatório',
            'numeric'  => 'O saldo deve ser numérico'
        ],
        'tipo' => [
            'required' => 'O tipo da carteira é obrigatório',
            'in_list'  => 'O tipo deve ser um dos valores aceitos'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setDefaults'];
    protected $afterInsert  = ['checkPrincipal'];
    protected $afterUpdate  = ['checkPrincipal'];
    
    protected function setDefaults(array $data)
    {
        // Se é a primeira carteira, torná-la principal automaticamente
        if (!isset($data['data']['principal'])) {
            // Verificar se já existe alguma carteira para o usuário
            $carteiraExistente = $this->where('usuario_id', $data['data']['usuario_id'])->first();
            
            // Se não existe, esta é a primeira e será principal
            if (!$carteiraExistente) {
                $data['data']['principal'] = 1;
            } else {
                $data['data']['principal'] = 0;
            }
        }
        
        // Se não definir ativo, é ativo por padrão
        if (!isset($data['data']['ativo'])) {
            $data['data']['ativo'] = 1;
        }
        
        // Se não definir ícone, usar um padrão de acordo com o tipo
        if (!isset($data['data']['icone']) || empty($data['data']['icone'])) {
            $icones = [
                'conta_corrente'  => 'fa-university',
                'conta_poupanca'  => 'fa-piggy-bank',
                'dinheiro'        => 'fa-wallet',
                'investimento'    => 'fa-chart-line',
                'cartao_credito'  => 'fa-credit-card',
                'outros'          => 'fa-money-bill-alt'
            ];
            
            $data['data']['icone'] = $icones[$data['data']['tipo']] ?? 'fa-wallet';
        }
        
        // Se não definir cor, usar uma padrão de acordo com o tipo
        if (!isset($data['data']['cor']) || empty($data['data']['cor'])) {
            $cores = [
                'conta_corrente'  => '#0066cc',
                'conta_poupanca'  => '#33cc33',
                'dinheiro'        => '#009900',
                'investimento'    => '#990099',
                'cartao_credito'  => '#cc0000',
                'outros'          => '#666666'
            ];
            
            $data['data']['cor'] = $cores[$data['data']['tipo']] ?? '#693976'; // Roxo padrão FinanceBye
        }
        
        return $data;
    }
    
    // Garantir que sempre haja apenas uma carteira principal por usuário
    protected function checkPrincipal(array $data)
    {
        // Se a carteira foi definida como principal
        if (isset($data['data']['principal']) && $data['data']['principal']) {
            // Obter a carteira com todas suas informações (inserção/atualização)
            $carteira = $this->find(isset($data['id']) ? $data['id'] : $data['data']['id']);
            
            // Remover flag principal de todas as outras carteiras do usuário
            $this->builder()
                 ->where('usuario_id', $carteira['usuario_id'])
                 ->where('id !=', $carteira['id'])
                 ->set(['principal' => 0])
                 ->update();
        }
        
        // Se a única carteira principal foi removida, definir outra como principal
        elseif (isset($data['data']['ativo']) && !$data['data']['ativo']) {
            $carteira = $this->find(isset($data['id']) ? $data['id'] : $data['data']['id']);
            
            if ($carteira['principal']) {
                // Buscar outra carteira ativa do usuário para ser a principal
                $outraCarteira = $this->where('usuario_id', $carteira['usuario_id'])
                                      ->where('id !=', $carteira['id'])
                                      ->where('ativo', 1)
                                      ->first();
                                      
                if ($outraCarteira) {
                    $this->update($outraCarteira['id'], ['principal' => 1]);
                }
            }
        }
        
        return $data;
    }
    
    // Obter todas as carteiras ativas do usuário
    public function getCarteirasAtivas($usuarioId)
    {
        return $this->where('usuario_id', $usuarioId)
                    ->where('ativo', 1)
                    ->orderBy('principal', 'DESC')
                    ->orderBy('nome', 'ASC')
                    ->findAll();
    }
    
    // Obter carteira principal do usuário
    public function getCarteiraPrincipal($usuarioId)
    {
        return $this->where('usuario_id', $usuarioId)
                    ->where('principal', 1)
                    ->first();
    }
    
    // Calcular saldo total de todas as carteiras
    public function getSaldoTotal($usuarioId)
    {
        return $this->selectSum('saldo')
                    ->where('usuario_id', $usuarioId)
                    ->where('ativo', 1)
                    ->first()['saldo'] ?? 0;
    }
    
    // Obter detalhes de saldos por tipo de carteira
    public function getSaldosPorTipo($usuarioId)
    {
        return $this->select('tipo, SUM(saldo) as saldo_total, COUNT(*) as quantidade')
                    ->where('usuario_id', $usuarioId)
                    ->where('ativo', 1)
                    ->groupBy('tipo')
                    ->findAll();
    }
} 