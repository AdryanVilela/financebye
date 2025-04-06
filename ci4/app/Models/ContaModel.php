<?php

namespace App\Models;

use CodeIgniter\Model;

class ContaModel extends Model
{
    protected $table            = 'contas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tipo',
        'descricao',
        'valor',
        'data_emissao',
        'data_vencimento',
        'data_pagamento',
        'status',
        'categoria_id',
        'cliente_id',
        'fornecedor_id',
        'observacoes',
        'usuario_id',
        'empresa_id',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'usuario_id'        => 'required|numeric',
        'descricao'         => 'required|min_length[3]|max_length[100]',
        'valor'             => 'required|numeric',
        'data_vencimento'   => 'required|valid_date',
        'tipo'              => 'required|in_list[pagar,receber]',
    ];
    
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'O ID do usuário é obrigatório',
            'numeric'  => 'O ID do usuário deve ser numérico'
        ],
        'descricao' => [
            'required'    => 'A descrição da conta é obrigatória',
            'min_length'  => 'A descrição deve ter pelo menos {param} caracteres',
            'max_length'  => 'A descrição não pode ter mais de {param} caracteres'
        ],
        'valor' => [
            'required' => 'O valor é obrigatório',
            'numeric'  => 'O valor deve ser numérico'
        ],
        'data_vencimento' => [
            'required'   => 'A data de vencimento é obrigatória',
            'valid_date' => 'A data de vencimento deve ser uma data válida'
        ],
        'tipo' => [
            'required'  => 'O tipo da conta é obrigatório',
            'in_list'   => 'O tipo deve ser pagar ou receber'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['setDefaults'];
    protected $afterInsert  = ['gerarParcelasRecorrentes'];
    protected $afterUpdate  = ['atualizarStatusRecorrentes'];
    
    protected function setDefaults(array $data)
    {
        // Definir status padrão
        if (!isset($data['data']['status'])) {
            $data['data']['status'] = 'pendente';
        }
        
        // Definir campos para contas recorrentes
        if (isset($data['data']['recorrente']) && $data['data']['recorrente']) {
            // Se é uma conta recorrente, mas não tem frequência
            if (!isset($data['data']['frequencia']) || empty($data['data']['frequencia'])) {
                $data['data']['frequencia'] = 'mensal';
            }
            
            // Se é uma conta recorrente, mas não tem total de parcelas, deixar aberto
            if (!isset($data['data']['total_parcelas']) || $data['data']['total_parcelas'] < 1) {
                $data['data']['total_parcelas'] = 0; // 0 = ilimitado
            }
            
            // Primeira parcela
            if (!isset($data['data']['parcela_atual'])) {
                $data['data']['parcela_atual'] = 1;
            }
        } else {
            // Não é recorrente
            $data['data']['recorrente'] = 0;
            $data['data']['parcela_atual'] = 1;
            $data['data']['total_parcelas'] = 1;
        }
        
        return $data;
    }
    
    // Gerar as próximas parcelas quando uma conta é criada como recorrente
    protected function gerarParcelasRecorrentes(array $data)
    {
        // Buscar a conta recém inserida
        $contaId = $data['id'];
        $conta = $this->find($contaId);
        
        // Se não é recorrente ou já tem conta pai (ou seja, é uma parcela)
        if (!$conta || !$conta['recorrente'] || isset($conta['conta_pai_id'])) {
            return $data;
        }
        
        // Só criar novas parcelas se tiver um total definido e for maior que 1
        if ($conta['total_parcelas'] > 1) {
            // Criar as próximas parcelas
            for ($i = 2; $i <= $conta['total_parcelas']; $i++) {
                $novaParcela = $conta;
                $novaParcela['conta_pai_id'] = $contaId;
                $novaParcela['parcela_atual'] = $i;
                $novaParcela['status'] = 'pendente';
                
                // Calcular próximo vencimento baseado na frequência
                $dataVencimento = new \DateTime($conta['data_vencimento']);
                
                switch ($conta['frequencia']) {
                    case 'semanal':
                        $dataVencimento->add(new \DateInterval('P' . (($i - 1) * 7) . 'D'));
                        break;
                    case 'quinzenal':
                        $dataVencimento->add(new \DateInterval('P' . (($i - 1) * 15) . 'D'));
                        break;
                    case 'mensal':
                    default:
                        $dataVencimento->add(new \DateInterval('P' . ($i - 1) . 'M'));
                        break;
                    case 'bimestral':
                        $dataVencimento->add(new \DateInterval('P' . (($i - 1) * 2) . 'M'));
                        break;
                    case 'trimestral':
                        $dataVencimento->add(new \DateInterval('P' . (($i - 1) * 3) . 'M'));
                        break;
                    case 'semestral':
                        $dataVencimento->add(new \DateInterval('P' . (($i - 1) * 6) . 'M'));
                        break;
                    case 'anual':
                        $dataVencimento->add(new \DateInterval('P' . ($i - 1) . 'Y'));
                        break;
                }
                
                $novaParcela['data_vencimento'] = $dataVencimento->format('Y-m-d');
                unset($novaParcela['id']); // Remover ID para inserir novo
                
                // Inserir sem chamar os callbacks para evitar loop
                $this->insert($novaParcela);
            }
        }
        
        return $data;
    }
    
    // Atualizar status das parcelas relacionadas quando a conta pai é atualizada
    protected function atualizarStatusRecorrentes(array $data)
    {
        // Se a conta for paga, verificar se é parte de uma recorrência
        if (isset($data['data']['status']) && $data['data']['status'] === 'pago') {
            $contaId = $data['id'];
            $conta = $this->find($contaId);
            
            // Se tem conta pai, verificar se todas as parcelas anteriores estão pagas
            if ($conta && isset($conta['conta_pai_id']) && $conta['conta_pai_id']) {
                // Buscar todas as parcelas dessa recorrência
                $parcelas = $this->where('conta_pai_id', $conta['conta_pai_id'])
                                 ->orWhere('id', $conta['conta_pai_id'])
                                 ->orderBy('parcela_atual', 'ASC')
                                 ->findAll();
                
                // Verificar se alguma parcela anterior está pendente
                $parcelaAtual = $conta['parcela_atual'];
                foreach ($parcelas as $parcela) {
                    if ($parcela['parcela_atual'] < $parcelaAtual && $parcela['status'] !== 'pago') {
                        // Alertar que há parcelas anteriores pendentes
                        // (implementar notificação)
                        break;
                    }
                }
            }
        }
        
        return $data;
    }
    
    // Buscar contas por período
    public function getContasPeriodo($usuarioId, $dataInicio, $dataFim, $status = null, $tipo = null)
    {
        $builder = $this->builder()
            ->select('contas.*, categorias.nome as categoria_nome, categorias.icone as categoria_icone')
            ->join('categorias', 'categorias.id = contas.categoria_id', 'left')
            ->where('contas.usuario_id', $usuarioId)
            ->where('contas.data_vencimento >=', $dataInicio)
            ->where('contas.data_vencimento <=', $dataFim);
            
        if ($status) {
            $builder->where('contas.status', $status);
        }
        
        if ($tipo) {
            $builder->where('contas.tipo', $tipo);
        }
        
        $builder->orderBy('contas.data_vencimento', 'ASC');
        
        return $builder->get()->getResultArray();
    }
    
    // Buscar contas vencidas
    public function getContasVencidas($usuarioId)
    {
        $hoje = date('Y-m-d');
        
        return $this->builder()
            ->select('contas.*, categorias.nome as categoria_nome, categorias.icone as categoria_icone')
            ->join('categorias', 'categorias.id = contas.categoria_id', 'left')
            ->where('contas.usuario_id', $usuarioId)
            ->where('contas.status', 'pendente')
            ->where('contas.data_vencimento <', $hoje)
            ->orderBy('contas.data_vencimento', 'ASC')
            ->get()
            ->getResultArray();
    }
    
    // Buscar próximas contas a vencer
    public function getProximasContas($usuarioId, $dias = 7)
    {
        $hoje = date('Y-m-d');
        $limite = date('Y-m-d', strtotime("+{$dias} days"));
        
        return $this->builder()
            ->select('contas.*, categorias.nome as categoria_nome, categorias.icone as categoria_icone')
            ->join('categorias', 'categorias.id = contas.categoria_id', 'left')
            ->where('contas.usuario_id', $usuarioId)
            ->where('contas.status', 'pendente')
            ->where('contas.data_vencimento >=', $hoje)
            ->where('contas.data_vencimento <=', $limite)
            ->orderBy('contas.data_vencimento', 'ASC')
            ->get()
            ->getResultArray();
    }
    
    // Obter estatísticas de pagamento
    public function getEstatisticas($usuarioId, $mes, $ano)
    {
        $primeiroDia = "{$ano}-{$mes}-01";
        $ultimoDia = date('Y-m-t', strtotime($primeiroDia));
        
        $estatisticas = [
            'total_pagar' => 0,
            'total_receber' => 0,
            'pago' => 0,
            'recebido' => 0,
            'pendente_pagar' => 0,
            'pendente_receber' => 0,
            'vencidas' => 0
        ];
        
        // Contas a pagar
        $contasPagar = $this->builder()
            ->select('SUM(valor) as total, status')
            ->where('usuario_id', $usuarioId)
            ->where('tipo', 'pagar')
            ->where('data_vencimento >=', $primeiroDia)
            ->where('data_vencimento <=', $ultimoDia)
            ->groupBy('status')
            ->get()
            ->getResultArray();
            
        foreach ($contasPagar as $conta) {
            $estatisticas['total_pagar'] += $conta['total'];
            
            if ($conta['status'] === 'pago') {
                $estatisticas['pago'] = $conta['total'];
            } else {
                $estatisticas['pendente_pagar'] += $conta['total'];
            }
        }
        
        // Contas a receber
        $contasReceber = $this->builder()
            ->select('SUM(valor) as total, status')
            ->where('usuario_id', $usuarioId)
            ->where('tipo', 'receber')
            ->where('data_vencimento >=', $primeiroDia)
            ->where('data_vencimento <=', $ultimoDia)
            ->groupBy('status')
            ->get()
            ->getResultArray();
            
        foreach ($contasReceber as $conta) {
            $estatisticas['total_receber'] += $conta['total'];
            
            if ($conta['status'] === 'pago') {
                $estatisticas['recebido'] = $conta['total'];
            } else {
                $estatisticas['pendente_receber'] += $conta['total'];
            }
        }
        
        // Contas vencidas
        $hoje = date('Y-m-d');
        $estatisticas['vencidas'] = $this->where('usuario_id', $usuarioId)
            ->where('status', 'pendente')
            ->where('data_vencimento <', $hoje)
            ->countAllResults();
            
        return $estatisticas;
    }
    
    // Obter saldo previsto (a receber - a pagar)
    public function getSaldoPrevisto($usuarioId, $mes, $ano)
    {
        $primeiroDia = "{$ano}-{$mes}-01";
        $ultimoDia = date('Y-m-t', strtotime($primeiroDia));
        
        // Total a receber
        $receber = $this->builder()
            ->selectSum('valor')
            ->where('usuario_id', $usuarioId)
            ->where('tipo', 'receber')
            ->where('data_vencimento >=', $primeiroDia)
            ->where('data_vencimento <=', $ultimoDia)
            ->get()
            ->getRowArray()['valor'] ?? 0;
            
        // Total a pagar
        $pagar = $this->builder()
            ->selectSum('valor')
            ->where('usuario_id', $usuarioId)
            ->where('tipo', 'pagar')
            ->where('data_vencimento >=', $primeiroDia)
            ->where('data_vencimento <=', $ultimoDia)
            ->get()
            ->getRowArray()['valor'] ?? 0;
            
        return $receber - $pagar;
    }

    /**
     * Busca contas por status
     */
    public function getByStatus($status, $tipo, $usuario_id, $empresa_id)
    {
        return $this->where('usuario_id', $usuario_id)
                   ->where('empresa_id', $empresa_id)
                   ->where('tipo', $tipo)
                   ->where('status', $status)
                   ->orderBy('data_vencimento', 'ASC')
                   ->findAll();
    }
    
    /**
     * Busca contas por período de vencimento
     */
    public function getByPeriodo($dataInicio, $dataFim, $tipo, $usuario_id, $empresa_id)
    {
        $builder = $this->where('usuario_id', $usuario_id)
                       ->where('empresa_id', $empresa_id)
                       ->where('tipo', $tipo);
        
        if ($dataInicio) {
            $builder->where('data_vencimento >=', $dataInicio);
        }
        
        if ($dataFim) {
            $builder->where('data_vencimento <=', $dataFim);
        }
        
        return $builder->orderBy('data_vencimento', 'ASC')->findAll();
    }
    
    /**
     * Calcula totais por status
     */
    public function getTotaisPorStatus($tipo, $usuario_id, $empresa_id)
    {
        $statusField = $tipo === 'receber' ? 'recebido' : 'pago';
        
        $totalPendente = $this->selectSum('valor')
                             ->where('usuario_id', $usuario_id)
                             ->where('empresa_id', $empresa_id)
                             ->where('tipo', $tipo)
                             ->where('status', 'pendente')
                             ->get()
                             ->getRow()
                             ->valor ?? 0;
        
        $totalConcluido = $this->selectSum('valor')
                              ->where('usuario_id', $usuario_id)
                              ->where('empresa_id', $empresa_id)
                              ->where('tipo', $tipo)
                              ->where('status', 'concluido')
                              ->get()
                              ->getRow()
                              ->valor ?? 0;
        
        $totalAtrasado = $this->selectSum('valor')
                            ->where('usuario_id', $usuario_id)
                            ->where('empresa_id', $empresa_id)
                            ->where('tipo', $tipo)
                            ->where('status', 'atrasado')
                            ->get()
                            ->getRow()
                            ->valor ?? 0;
        
        $totalGeral = $totalPendente + $totalConcluido + $totalAtrasado;
        
        return [
            'pendente'  => $totalPendente,
            'concluido' => $totalConcluido,
            'atrasado'  => $totalAtrasado,
            'total'     => $totalGeral
        ];
    }
    
    /**
     * Atualiza status de contas atrasadas
     */
    public function atualizarStatusAtrasado($usuario_id, $empresa_id)
    {
        $hoje = date('Y-m-d');
        
        // Atualizar contas pendentes com vencimento anterior à data atual para "atrasado"
        return $this->where('usuario_id', $usuario_id)
                   ->where('empresa_id', $empresa_id)
                   ->where('status', 'pendente')
                   ->where('data_vencimento <', $hoje)
                   ->set(['status' => 'atrasado'])
                   ->update();
    }
    
    /**
     * Busca contas por cliente
     */
    public function getByCliente($cliente_id, $usuario_id, $empresa_id)
    {
        return $this->where('usuario_id', $usuario_id)
                   ->where('empresa_id', $empresa_id)
                   ->where('cliente_id', $cliente_id)
                   ->orderBy('data_vencimento', 'ASC')
                   ->findAll();
    }
    
    /**
     * Busca contas por fornecedor
     */
    public function getByFornecedor($fornecedor_id, $usuario_id, $empresa_id)
    {
        return $this->where('usuario_id', $usuario_id)
                   ->where('empresa_id', $empresa_id)
                   ->where('fornecedor_id', $fornecedor_id)
                   ->orderBy('data_vencimento', 'ASC')
                   ->findAll();
    }
} 