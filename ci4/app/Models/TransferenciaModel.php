<?php

namespace App\Models;

use CodeIgniter\Model;

class TransferenciaModel extends Model
{
    protected $table            = 'transferencias';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'usuario_id', 'carteira_origem_id', 'carteira_destino_id', 
        'valor', 'data', 'descricao', 'taxa'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'usuario_id'         => 'required|numeric',
        'carteira_origem_id' => 'required|numeric',
        'carteira_destino_id'=> 'required|numeric|differs[carteira_origem_id]',
        'valor'              => 'required|numeric|greater_than[0]',
        'data'               => 'required|valid_date',
    ];
    
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'O ID do usuário é obrigatório',
            'numeric'  => 'O ID do usuário deve ser numérico'
        ],
        'carteira_origem_id' => [
            'required' => 'A carteira de origem é obrigatória',
            'numeric'  => 'O ID da carteira de origem deve ser numérico'
        ],
        'carteira_destino_id' => [
            'required' => 'A carteira de destino é obrigatória',
            'numeric'  => 'O ID da carteira de destino deve ser numérico',
            'differs'  => 'A carteira de destino deve ser diferente da carteira de origem'
        ],
        'valor' => [
            'required'      => 'O valor é obrigatório',
            'numeric'       => 'O valor deve ser numérico',
            'greater_than'  => 'O valor deve ser maior que {param}'
        ],
        'data' => [
            'required'   => 'A data é obrigatória',
            'valid_date' => 'A data deve ser uma data válida'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setDefaults'];
    protected $afterInsert  = ['atualizarSaldosCarteiras'];
    protected $afterUpdate  = ['atualizarSaldosCarteiras'];
    protected $beforeDelete = ['restaurarSaldosCarteiras'];
    
    protected function setDefaults(array $data)
    {
        // Se não definiu taxa, definir como 0
        if (!isset($data['data']['taxa'])) {
            $data['data']['taxa'] = 0;
        }
        
        // Se não definiu data, usar a data atual
        if (!isset($data['data']['data'])) {
            $data['data']['data'] = date('Y-m-d');
        }
        
        return $data;
    }
    
    // Atualizar os saldos das carteiras após inserir/atualizar transferência
    protected function atualizarSaldosCarteiras(array $data)
    {
        $transferencia = null;
        
        // Buscar os dados da transferência
        if (isset($data['id'])) {
            $transferencia = $this->find($data['id']);
        } elseif (isset($data['data']['id'])) {
            $transferencia = $this->find($data['data']['id']);
        }
        
        if (!$transferencia) {
            return $data;
        }
        
        // Buscar carteiras
        $carteiraModel = new \App\Models\CarteiraModel();
        $origem = $carteiraModel->find($transferencia['carteira_origem_id']);
        $destino = $carteiraModel->find($transferencia['carteira_destino_id']);
        
        if (!$origem || !$destino) {
            return $data;
        }
        
        // Valor total a transferir (incluindo taxa)
        $valorTotal = $transferencia['valor'] + $transferencia['taxa'];
        
        // Remover da carteira de origem
        $carteiraModel->update($origem['id'], [
            'saldo' => $origem['saldo'] - $valorTotal
        ]);
        
        // Adicionar à carteira de destino
        $carteiraModel->update($destino['id'], [
            'saldo' => $destino['saldo'] + $transferencia['valor']
        ]);
        
        return $data;
    }
    
    // Restaurar os saldos das carteiras antes de deletar a transferência
    protected function restaurarSaldosCarteiras(array $data)
    {
        // ID da transferência a ser excluída
        $id = $data['id'][0] ?? null;
        
        if (!$id) {
            return $data;
        }
        
        // Buscar os dados da transferência
        $transferencia = $this->find($id);
        
        if (!$transferencia) {
            return $data;
        }
        
        // Buscar carteiras
        $carteiraModel = new \App\Models\CarteiraModel();
        $origem = $carteiraModel->find($transferencia['carteira_origem_id']);
        $destino = $carteiraModel->find($transferencia['carteira_destino_id']);
        
        if (!$origem || !$destino) {
            return $data;
        }
        
        // Valor total que foi transferido (incluindo taxa)
        $valorTotal = $transferencia['valor'] + $transferencia['taxa'];
        
        // Devolver à carteira de origem
        $carteiraModel->update($origem['id'], [
            'saldo' => $origem['saldo'] + $valorTotal
        ]);
        
        // Remover da carteira de destino
        $carteiraModel->update($destino['id'], [
            'saldo' => $destino['saldo'] - $transferencia['valor']
        ]);
        
        return $data;
    }
    
    // Obter transferências por período
    public function getTransferenciasPeriodo($usuarioId, $dataInicio, $dataFim, $carteiraId = null)
    {
        $builder = $this->builder()
            ->select('transferencias.*, co.nome as carteira_origem_nome, cd.nome as carteira_destino_nome')
            ->join('carteiras co', 'co.id = transferencias.carteira_origem_id')
            ->join('carteiras cd', 'cd.id = transferencias.carteira_destino_id')
            ->where('transferencias.usuario_id', $usuarioId)
            ->where('transferencias.data >=', $dataInicio)
            ->where('transferencias.data <=', $dataFim);
            
        // Filtrar por carteira específica (origem ou destino)
        if ($carteiraId) {
            $builder->groupStart()
                ->where('transferencias.carteira_origem_id', $carteiraId)
                ->orWhere('transferencias.carteira_destino_id', $carteiraId)
                ->groupEnd();
        }
        
        $builder->orderBy('transferencias.data', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    // Validar se há saldo suficiente na carteira de origem
    public function validarSaldoSuficiente($carteiraOrigemId, $valor, $taxa = 0)
    {
        $carteiraModel = new \App\Models\CarteiraModel();
        $carteira = $carteiraModel->find($carteiraOrigemId);
        
        if (!$carteira) {
            return false;
        }
        
        // Valor total a transferir (incluindo taxa)
        $valorTotal = $valor + $taxa;
        
        return $carteira['saldo'] >= $valorTotal;
    }
    
    // Obter estatísticas de transferências
    public function getEstatisticas($usuarioId, $mes = null, $ano = null)
    {
        if (!$mes) {
            $mes = date('m');
        }
        
        if (!$ano) {
            $ano = date('Y');
        }
        
        $primeiroDia = sprintf('%04d-%02d-01', $ano, $mes);
        $ultimoDia = date('Y-m-t', strtotime($primeiroDia));
        
        // Total transferido no período
        $totalTransferido = $this->selectSum('valor')
            ->where('usuario_id', $usuarioId)
            ->where('data >=', $primeiroDia)
            ->where('data <=', $ultimoDia)
            ->first()['valor'] ?? 0;
            
        // Total de taxas no período
        $totalTaxas = $this->selectSum('taxa')
            ->where('usuario_id', $usuarioId)
            ->where('data >=', $primeiroDia)
            ->where('data <=', $ultimoDia)
            ->first()['taxa'] ?? 0;
            
        // Quantidade de transferências no período
        $quantidadeTransferencias = $this->where('usuario_id', $usuarioId)
            ->where('data >=', $primeiroDia)
            ->where('data <=', $ultimoDia)
            ->countAllResults();
            
        // Carteira mais usada como origem
        $carteiraOrigem = $this->builder()
            ->select('carteira_origem_id, COUNT(*) as total')
            ->where('usuario_id', $usuarioId)
            ->where('data >=', $primeiroDia)
            ->where('data <=', $ultimoDia)
            ->groupBy('carteira_origem_id')
            ->orderBy('total', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
            
        // Carteira mais usada como destino
        $carteiraDestino = $this->builder()
            ->select('carteira_destino_id, COUNT(*) as total')
            ->where('usuario_id', $usuarioId)
            ->where('data >=', $primeiroDia)
            ->where('data <=', $ultimoDia)
            ->groupBy('carteira_destino_id')
            ->orderBy('total', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
            
        // Buscar nomes das carteiras
        $carteiraModel = new \App\Models\CarteiraModel();
        
        $carteiraOrigemNome = null;
        if ($carteiraOrigem && isset($carteiraOrigem['carteira_origem_id'])) {
            $carteira = $carteiraModel->find($carteiraOrigem['carteira_origem_id']);
            $carteiraOrigemNome = $carteira ? $carteira['nome'] : null;
        }
        
        $carteiraDestinoNome = null;
        if ($carteiraDestino && isset($carteiraDestino['carteira_destino_id'])) {
            $carteira = $carteiraModel->find($carteiraDestino['carteira_destino_id']);
            $carteiraDestinoNome = $carteira ? $carteira['nome'] : null;
        }
        
        return [
            'total_transferido' => $totalTransferido,
            'total_taxas' => $totalTaxas,
            'total_geral' => $totalTransferido + $totalTaxas,
            'quantidade' => $quantidadeTransferencias,
            'origem_mais_usada' => [
                'id' => $carteiraOrigem['carteira_origem_id'] ?? null,
                'nome' => $carteiraOrigemNome,
                'total' => $carteiraOrigem['total'] ?? 0
            ],
            'destino_mais_usado' => [
                'id' => $carteiraDestino['carteira_destino_id'] ?? null,
                'nome' => $carteiraDestinoNome,
                'total' => $carteiraDestino['total'] ?? 0
            ]
        ];
    }
} 