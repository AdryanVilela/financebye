<?php

namespace App\Models;

use CodeIgniter\Model;

class MetaModel extends Model
{
    protected $table            = 'metas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'usuario_id', 'empresa_id', 'titulo', 'descricao', 'valor_alvo', 'valor_atual',
        'data_inicio', 'data_alvo', 'categoria_id', 'icone', 'cor', 'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'usuario_id'   => 'required|numeric',
        'titulo'       => 'required|min_length[3]|max_length[100]',
        'valor_alvo'   => 'required|numeric',
        'data_alvo'    => 'required|valid_date',
    ];
    
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'O ID do usuário é obrigatório',
            'numeric'  => 'O ID do usuário deve ser numérico'
        ],
        'titulo' => [
            'required'    => 'O título da meta é obrigatório',
            'min_length'  => 'O título deve ter pelo menos {param} caracteres',
            'max_length'  => 'O título não pode ter mais de {param} caracteres'
        ],
        'valor_alvo' => [
            'required' => 'O valor alvo é obrigatório',
            'numeric'  => 'O valor alvo deve ser numérico'
        ],
        'data_alvo' => [
            'required'   => 'A data alvo é obrigatória',
            'valid_date' => 'A data alvo deve ser uma data válida'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setStatusInicial'];
    protected $beforeUpdate = ['atualizarProgresso'];
    
    protected function setStatusInicial(array $data)
    {
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'em_andamento';
        }
        
        return $data;
    }
    
    protected function atualizarProgresso(array $data)
    {
        // Se chegou no valor alvo, atualizar status
        if (isset($data['data']['valor_atual']) && isset($data['data']['valor_alvo'])) {
            if ($data['data']['valor_atual'] >= $data['data']['valor_alvo']) {
                $data['data']['status'] = 'concluida';
            } elseif ($data['data']['status'] === 'concluida') {
                $data['data']['status'] = 'em_andamento';
            }
        }
        
        return $data;
    }
    
    /**
     * Obter todas as metas de um usuário
     */
    public function getMetasUsuario($usuarioId)
    {
        $metas = $this->where('usuario_id', $usuarioId)
                      ->orderBy('data_alvo', 'ASC')
                      ->findAll();
        
        // Buscar nomes das categorias
        if (!empty($metas)) {
            $categoriaModel = new CategoriaModel();
            foreach ($metas as &$meta) {
                if (!empty($meta['categoria_id'])) {
                    $categoria = $categoriaModel->find($meta['categoria_id']);
                    $meta['categoria_nome'] = $categoria ? $categoria['nome'] : 'Categoria removida';
                } else {
                    $meta['categoria_nome'] = 'Sem categoria';
                }
            }
        }
        
        return $metas;
    }
    
    /**
     * Calcular o progresso da meta
     */
    public function calcularProgresso($metaId)
    {
        $meta = $this->find($metaId);
        
        if (!$meta) {
            return 0;
        }
        
        // Se o valor alvo for zero, evitar divisão por zero
        if ($meta['valor_alvo'] <= 0) {
            return 0;
        }
        
        // Calcular porcentagem de progresso
        $progresso = ($meta['valor_atual'] / $meta['valor_alvo']) * 100;
        
        // Limitar a 100%
        return min(100, $progresso);
    }
    
    /**
     * Verificar se o usuário atingiu a meta
     */
    public function verificarMetaConcluida($metaId)
    {
        $meta = $this->find($metaId);
        
        if (!$meta) {
            return false;
        }
        
        return ($meta['valor_atual'] >= $meta['valor_alvo']);
    }
    
    /**
     * Atualizar status da meta para concluída
     */
    public function concluirMeta($metaId)
    {
        return $this->update($metaId, ['status' => 'concluida']);
    }
    
    /**
     * Obter metas que estão próximas do prazo final
     */
    public function getMetasProximasDoPrazo($usuarioId, $diasLimite = 7)
    {
        $hoje = date('Y-m-d');
        $limite = date('Y-m-d', strtotime("+{$diasLimite} days"));
        
        return $this->where('usuario_id', $usuarioId)
                    ->where('status', 'em_andamento')
                    ->where('data_alvo >=', $hoje)
                    ->where('data_alvo <=', $limite)
                    ->findAll();
    }
    
    /**
     * Obter metas atrasadas
     */
    public function getMetasAtrasadas($usuarioId)
    {
        $hoje = date('Y-m-d');
        
        return $this->where('usuario_id', $usuarioId)
                    ->where('status', 'em_andamento')
                    ->where('data_alvo <', $hoje)
                    ->findAll();
    }
} 