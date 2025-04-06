<?php

namespace App\Models;

use CodeIgniter\Model;

class ContasPagarModel extends Model
{
    protected $table = 'contas_pagar';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'descricao', 
        'valor', 
        'data_emissao', 
        'data_vencimento', 
        'data_pagamento', 
        'status', 
        'categoria_id', 
        'observacoes', 
        'usuario_id'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Obter contas por status
    public function getByStatus($status, $usuario_id)
    {
        return $this->where('status', $status)
                    ->where('usuario_id', $usuario_id)
                    ->orderBy('data_vencimento', 'ASC')
                    ->findAll();
    }

    // Obter contas com vencimento no período
    public function getByPeriodo($dataInicio, $dataFim, $usuario_id)
    {
        return $this->where('data_vencimento >=', $dataInicio)
                    ->where('data_vencimento <=', $dataFim)
                    ->where('usuario_id', $usuario_id)
                    ->orderBy('data_vencimento', 'ASC')
                    ->findAll();
    }

    // Obter totais por status
    public function getTotaisPorStatus($usuario_id)
    {
        $result = [
            'pendente' => 0,
            'pago' => 0,
            'atrasado' => 0,
            'total' => 0
        ];

        $contas = $this->where('usuario_id', $usuario_id)->findAll();
        
        foreach ($contas as $conta) {
            if ($conta['status'] == 'pendente') {
                $result['pendente'] += $conta['valor'];
            } else if ($conta['status'] == 'pago') {
                $result['pago'] += $conta['valor'];
            } else if ($conta['status'] == 'atrasado') {
                $result['atrasado'] += $conta['valor'];
            }
            
            $result['total'] += $conta['valor'];
        }
        
        return $result;
    }
    
    // Atualizar status para atrasado automaticamente
    public function atualizarStatusAtrasado()
    {
        $hoje = date('Y-m-d');
        
        return $this->set('status', 'atrasado')
                    ->where('status', 'pendente')
                    ->where('data_vencimento <', $hoje)
                    ->update();
    }
} 