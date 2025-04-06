<?php

namespace App\Models;

use CodeIgniter\Model;

class OrcamentoModel extends Model
{
    protected $table            = 'orcamentos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'usuario_id', 'categoria_id', 'valor_limite', 'mes', 'ano', 
        'notificar_em', 'notificado', 'descricao'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'usuario_id'    => 'required|numeric',
        'categoria_id'  => 'required|numeric',
        'valor_limite'  => 'required|numeric',
        'mes'           => 'required|numeric|less_than_equal_to[12]|greater_than_equal_to[1]',
        'ano'           => 'required|numeric|greater_than_equal_to[2020]',
    ];
    
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'O ID do usuário é obrigatório',
            'numeric'  => 'O ID do usuário deve ser numérico'
        ],
        'categoria_id' => [
            'required' => 'A categoria é obrigatória',
            'numeric'  => 'O ID da categoria deve ser numérico'
        ],
        'valor_limite' => [
            'required' => 'O valor limite é obrigatório',
            'numeric'  => 'O valor limite deve ser numérico'
        ],
        'mes' => [
            'required'                => 'O mês é obrigatório',
            'numeric'                 => 'O mês deve ser numérico',
            'less_than_equal_to'      => 'O mês deve ser menor ou igual a {param}',
            'greater_than_equal_to'   => 'O mês deve ser maior ou igual a {param}'
        ],
        'ano' => [
            'required'                => 'O ano é obrigatório',
            'numeric'                 => 'O ano deve ser numérico',
            'greater_than_equal_to'   => 'O ano deve ser maior ou igual a {param}'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setDefaults'];
    
    protected function setDefaults(array $data)
    {
        if (!isset($data['data']['notificar_em'])) {
            // Por padrão, notificar quando atingir 80% do limite
            $data['data']['notificar_em'] = 80;
        }
        
        if (!isset($data['data']['notificado'])) {
            $data['data']['notificado'] = 0;
        }
        
        return $data;
    }
    
    // Buscar orçamentos de um mês/ano específico por usuário
    public function getOrcamentosUsuario($usuarioId, $mes = null, $ano = null)
    {
        $builder = $this->builder()
            ->select('orcamentos.*, categorias.nome as categoria_nome, categorias.tipo as categoria_tipo, categorias.icone as categoria_icone')
            ->join('categorias', 'categorias.id = orcamentos.categoria_id')
            ->where('orcamentos.usuario_id', $usuarioId);
            
        if ($mes) {
            $builder->where('orcamentos.mes', $mes);
        }
        
        if ($ano) {
            $builder->where('orcamentos.ano', $ano);
        }
        
        $builder->orderBy('categorias.tipo', 'ASC')
                ->orderBy('categorias.nome', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    // Calcular o progresso do orçamento (gastos atuais vs limite)
    public function calcularProgresso($orcamentoId, $mes = null, $ano = null)
    {
        $orcamento = $this->find($orcamentoId);
        
        if (!$orcamento) {
            return 0;
        }
        
        // Se não passar mês/ano, usar os valores do orçamento
        $mes = $mes ?? $orcamento['mes'];
        $ano = $ano ?? $orcamento['ano'];
        
        // Buscar a soma das transações da categoria no período
        $transacaoModel = new \App\Models\TransacaoModel();
        $builder = $transacaoModel->builder();
        
        $builder->select('SUM(valor) as total')
                ->where('categoria_id', $orcamento['categoria_id'])
                ->where('usuario_id', $orcamento['usuario_id'])
                ->where('MONTH(data)', $mes)
                ->where('YEAR(data)', $ano);
        
        $resultado = $builder->get()->getRowArray();
        $valorGasto = $resultado['total'] ?? 0;
        
        // Verificar se precisa notificar o usuário
        $this->verificarNotificacao($orcamentoId, $valorGasto, $orcamento['valor_limite']);
        
        if ($orcamento['valor_limite'] <= 0) {
            return 0;
        }
        
        $progresso = ($valorGasto / $orcamento['valor_limite']) * 100;
        
        return round($progresso, 2);
    }
    
    // Verificar se o limite está próximo e atualizar status de notificação
    protected function verificarNotificacao($orcamentoId, $valorGasto, $valorLimite)
    {
        $orcamento = $this->find($orcamentoId);
        
        if (!$orcamento || $orcamento['notificado']) {
            return;
        }
        
        $percentualGasto = ($valorGasto / $valorLimite) * 100;
        
        if ($percentualGasto >= $orcamento['notificar_em']) {
            // Marcar como notificado
            $this->update($orcamentoId, ['notificado' => 1]);
            
            // Aqui seria implementada a lógica de envio de notificação
            // (email, push, etc)
        }
    }
    
    // Obter gastos agrupados por categoria para um período específico
    public function getGastosPorCategoria($usuarioId, $mes, $ano)
    {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                c.id as categoria_id,
                c.nome as categoria_nome,
                c.icone as categoria_icone,
                c.tipo as categoria_tipo,
                COALESCE(SUM(t.valor), 0) as valor_gasto,
                o.valor_limite as valor_limite,
                o.id as orcamento_id,
                CASE 
                    WHEN o.valor_limite > 0 THEN ROUND((COALESCE(SUM(t.valor), 0) / o.valor_limite) * 100, 2)
                    ELSE 0
                END as progresso
            FROM 
                categorias c
            LEFT JOIN 
                transacoes t ON c.id = t.categoria_id AND t.usuario_id = ? AND MONTH(t.data) = ? AND YEAR(t.data) = ? AND t.deleted_at IS NULL
            LEFT JOIN 
                orcamentos o ON c.id = o.categoria_id AND o.usuario_id = ? AND o.mes = ? AND o.ano = ? AND o.deleted_at IS NULL
            WHERE 
                c.tipo = 'despesa' AND c.deleted_at IS NULL
            GROUP BY 
                c.id, o.id
            ORDER BY 
                valor_gasto DESC
        ", [$usuarioId, $mes, $ano, $usuarioId, $mes, $ano]);
        
        return $query->getResultArray();
    }
} 