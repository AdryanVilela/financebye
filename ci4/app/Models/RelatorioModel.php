<?php

namespace App\Models;

use CodeIgniter\Model;

class RelatorioModel extends Model
{
    protected $table            = 'relatorios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'usuario_id', 'nome', 'descricao', 'tipo', 'filtros', 'periodo',
        'data_inicio', 'data_fim', 'categorias', 'carteiras', 'agrupamento',
        'campos', 'ordenacao', 'formato', 'agendamento', 'ultima_execucao',
        'compartilhado'
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
        'nome'          => 'required|min_length[3]|max_length[100]',
        'tipo'          => 'required|in_list[transacoes,orcamento,contas,fluxo_caixa,categorias,carteiras]',
    ];
    
    protected $validationMessages = [
        'usuario_id' => [
            'required' => 'O ID do usuário é obrigatório',
            'numeric'  => 'O ID do usuário deve ser numérico'
        ],
        'nome' => [
            'required'    => 'O nome do relatório é obrigatório',
            'min_length'  => 'O nome deve ter pelo menos {param} caracteres',
            'max_length'  => 'O nome não pode ter mais de {param} caracteres'
        ],
        'tipo' => [
            'required' => 'O tipo do relatório é obrigatório',
            'in_list'  => 'O tipo deve ser um dos valores aceitos'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['serializeFiltros', 'atualizarUltimaExecucao'];
    protected $beforeUpdate = ['serializeFiltros', 'atualizarUltimaExecucao'];
    protected $afterFind    = ['deserializeFiltros'];
    
    protected function serializeFiltros(array $data)
    {
        // Serializar campos que são arrays
        $camposJson = ['filtros', 'categorias', 'carteiras', 'campos', 'ordenacao'];
        
        foreach ($camposJson as $campo) {
            if (isset($data['data'][$campo]) && is_array($data['data'][$campo])) {
                $data['data'][$campo] = json_encode($data['data'][$campo]);
            }
        }
        
        return $data;
    }
    
    protected function deserializeFiltros(array $data)
    {
        // Deserializar campos que são arrays
        $camposJson = ['filtros', 'categorias', 'carteiras', 'campos', 'ordenacao'];
        
        // Verificar se estamos processando um único resultado ou vários
        if (isset($data['result'])) {
            // Resultado único
            $result = $data['result'];
            
            foreach ($camposJson as $campo) {
                if (isset($result[$campo]) && is_string($result[$campo])) {
                    $data['result'][$campo] = json_decode($result[$campo], true) ?? [];
                }
            }
        } elseif (isset($data['results'])) {
            // Múltiplos resultados
            foreach ($data['results'] as $key => $result) {
                foreach ($camposJson as $campo) {
                    if (isset($result[$campo]) && is_string($result[$campo])) {
                        $data['results'][$key][$campo] = json_decode($result[$campo], true) ?? [];
                    }
                }
            }
        }
        
        return $data;
    }
    
    protected function atualizarUltimaExecucao(array $data)
    {
        // Se estiver sendo executado o relatório, atualizar a data de última execução
        if (isset($data['data']['executando']) && $data['data']['executando']) {
            $data['data']['ultima_execucao'] = date('Y-m-d H:i:s');
            unset($data['data']['executando']); // Remover campo auxiliar
        }
        
        return $data;
    }
    
    // Buscar relatórios por usuário
    public function getRelatoriosUsuario($usuarioId, $tipo = null)
    {
        $builder = $this->builder()->where('usuario_id', $usuarioId);
        
        if ($tipo) {
            $builder->where('tipo', $tipo);
        }
        
        $builder->orderBy('created_at', 'DESC');
        
        return $this->deserializeFiltros(['results' => $builder->get()->getResultArray()])['results'];
    }
    
    // Executar o relatório e retornar os dados formatados
    public function executarRelatorio($relatorioId)
    {
        $relatorio = $this->find($relatorioId);
        
        if (!$relatorio) {
            return [
                'status' => 'error',
                'message' => 'Relatório não encontrado'
            ];
        }
        
        // Marcar como executado
        $this->update($relatorioId, ['executando' => true]);
        
        // Estrutura padrão para resposta
        $resultado = [
            'status' => 'success',
            'titulo' => $relatorio['nome'],
            'descricao' => $relatorio['descricao'],
            'tipo' => $relatorio['tipo'],
            'data_geracao' => date('Y-m-d H:i:s'),
            'filtros' => $relatorio['filtros'],
            'dados' => []
        ];
        
        // Obter dados de acordo com o tipo de relatório
        switch ($relatorio['tipo']) {
            case 'transacoes':
                $resultado['dados'] = $this->getDadosTransacoes($relatorio);
                break;
                
            case 'orcamento':
                $resultado['dados'] = $this->getDadosOrcamento($relatorio);
                break;
                
            case 'contas':
                $resultado['dados'] = $this->getDadosContas($relatorio);
                break;
                
            case 'fluxo_caixa':
                $resultado['dados'] = $this->getDadosFluxoCaixa($relatorio);
                break;
                
            case 'categorias':
                $resultado['dados'] = $this->getDadosCategorias($relatorio);
                break;
                
            case 'carteiras':
                $resultado['dados'] = $this->getDadosCarteiras($relatorio);
                break;
                
            default:
                $resultado['status'] = 'error';
                $resultado['message'] = 'Tipo de relatório não suportado';
                return $resultado;
        }
        
        // Adicionar resumo
        $resultado['resumo'] = $this->gerarResumo($resultado['dados'], $relatorio);
        
        return $resultado;
    }
    
    // Métodos auxiliares para obtenção de dados específicos
    private function getDadosTransacoes($relatorio)
    {
        $transacaoModel = new \App\Models\TransacaoModel();
        $builder = $transacaoModel->builder();
        
        // Adicionar joins e selects básicos
        $builder->select('transacoes.*, categorias.nome as categoria_nome, categorias.tipo as categoria_tipo')
                ->join('categorias', 'categorias.id = transacoes.categoria_id')
                ->where('transacoes.usuario_id', $relatorio['usuario_id']);
        
        // Aplicar filtros de período
        $this->aplicarFiltrosPeriodo($builder, $relatorio);
        
        // Aplicar filtros de categorias
        if (!empty($relatorio['categorias'])) {
            $builder->whereIn('transacoes.categoria_id', $relatorio['categorias']);
        }
        
        // Aplicar filtros de carteiras
        if (!empty($relatorio['carteiras'])) {
            $builder->whereIn('transacoes.carteira_id', $relatorio['carteiras']);
        }
        
        // Aplicar ordenação
        if (!empty($relatorio['ordenacao'])) {
            foreach ($relatorio['ordenacao'] as $campo => $direcao) {
                $builder->orderBy($campo, $direcao);
            }
        } else {
            // Ordenação padrão
            $builder->orderBy('transacoes.data', 'DESC');
        }
        
        // Executar a consulta
        $transacoes = $builder->get()->getResultArray();
        
        // Formatação adicional
        foreach ($transacoes as &$transacao) {
            $transacao['valor_formatado'] = 'R$ ' . number_format(abs($transacao['valor']), 2, ',', '.');
            $transacao['data_formatada'] = date('d/m/Y', strtotime($transacao['data']));
        }
        
        return $transacoes;
    }
    
    private function getDadosOrcamento($relatorio)
    {
        $orcamentoModel = new \App\Models\OrcamentoModel();
        
        // Obter mês e ano do período
        $mes = date('m');
        $ano = date('Y');
        
        if ($relatorio['periodo'] === 'personalizado' && !empty($relatorio['data_inicio'])) {
            $data = new \DateTime($relatorio['data_inicio']);
            $mes = $data->format('m');
            $ano = $data->format('Y');
        }
        
        // Buscar gastos por categoria
        return $orcamentoModel->getGastosPorCategoria($relatorio['usuario_id'], $mes, $ano);
    }
    
    private function getDadosContas($relatorio)
    {
        $contaModel = new \App\Models\ContaModel();
        
        // Definir período padrão (mês atual)
        $hoje = date('Y-m-d');
        $primeiroDia = date('Y-m-01');
        $ultimoDia = date('Y-m-t');
        
        // Aplicar filtros de período
        if ($relatorio['periodo'] === 'personalizado') {
            if (!empty($relatorio['data_inicio'])) {
                $primeiroDia = $relatorio['data_inicio'];
            }
            
            if (!empty($relatorio['data_fim'])) {
                $ultimoDia = $relatorio['data_fim'];
            }
        }
        
        // Buscar contas do período
        $contas = $contaModel->getContasPeriodo(
            $relatorio['usuario_id'], 
            $primeiroDia, 
            $ultimoDia, 
            $relatorio['filtros']['status'] ?? null,
            $relatorio['filtros']['tipo'] ?? null
        );
        
        // Formatação adicional
        foreach ($contas as &$conta) {
            $conta['valor_formatado'] = 'R$ ' . number_format($conta['valor'], 2, ',', '.');
            $conta['vencimento_formatado'] = date('d/m/Y', strtotime($conta['data_vencimento']));
            
            if (!empty($conta['data_pagamento'])) {
                $conta['pagamento_formatado'] = date('d/m/Y', strtotime($conta['data_pagamento']));
            } else {
                $conta['pagamento_formatado'] = '-';
            }
        }
        
        return $contas;
    }
    
    private function getDadosFluxoCaixa($relatorio)
    {
        $transacaoModel = new \App\Models\TransacaoModel();
        
        // Definir período padrão (mês atual)
        $hoje = date('Y-m-d');
        $primeiroDia = date('Y-m-01');
        $ultimoDia = date('Y-m-t');
        
        // Aplicar filtros de período
        if ($relatorio['periodo'] === 'personalizado') {
            if (!empty($relatorio['data_inicio'])) {
                $primeiroDia = $relatorio['data_inicio'];
            }
            
            if (!empty($relatorio['data_fim'])) {
                $ultimoDia = $relatorio['data_fim'];
            }
        }
        
        // Definir agrupamento
        $agrupamento = $relatorio['agrupamento'] ?? 'dia';
        $formatoData = 'Y-m-d';
        $sqlFormat = '%Y-%m-%d';
        
        if ($agrupamento === 'mes') {
            $formatoData = 'Y-m';
            $sqlFormat = '%Y-%m';
        } elseif ($agrupamento === 'ano') {
            $formatoData = 'Y';
            $sqlFormat = '%Y';
        }
        
        // Buscar transações agrupadas
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                DATE_FORMAT(data, '{$sqlFormat}') as periodo,
                COALESCE(SUM(CASE WHEN c.tipo = 'receita' THEN valor ELSE 0 END), 0) as receitas,
                COALESCE(SUM(CASE WHEN c.tipo = 'despesa' THEN valor ELSE 0 END), 0) as despesas,
                COALESCE(SUM(CASE WHEN c.tipo = 'receita' THEN valor ELSE -valor END), 0) as saldo
            FROM 
                transacoes t
            JOIN 
                categorias c ON t.categoria_id = c.id
            WHERE 
                t.usuario_id = ? AND
                t.data BETWEEN ? AND ? AND
                t.deleted_at IS NULL
            GROUP BY 
                periodo
            ORDER BY 
                periodo ASC
        ", [$relatorio['usuario_id'], $primeiroDia, $ultimoDia]);
        
        $resultados = $query->getResultArray();
        
        // Formatação adicional
        foreach ($resultados as &$resultado) {
            $resultado['receitas_formatado'] = 'R$ ' . number_format($resultado['receitas'], 2, ',', '.');
            $resultado['despesas_formatado'] = 'R$ ' . number_format(abs($resultado['despesas']), 2, ',', '.');
            $resultado['saldo_formatado'] = 'R$ ' . number_format($resultado['saldo'], 2, ',', '.');
            
            // Formatar o período para exibição
            if ($agrupamento === 'dia') {
                $data = \DateTime::createFromFormat('Y-m-d', $resultado['periodo']);
                $resultado['periodo_formatado'] = $data ? $data->format('d/m/Y') : $resultado['periodo'];
            } elseif ($agrupamento === 'mes') {
                $data = \DateTime::createFromFormat('Y-m', $resultado['periodo']);
                $resultado['periodo_formatado'] = $data ? $data->format('m/Y') : $resultado['periodo'];
            } else {
                $resultado['periodo_formatado'] = $resultado['periodo'];
            }
        }
        
        return $resultados;
    }
    
    private function getDadosCategorias($relatorio)
    {
        $transacaoModel = new \App\Models\TransacaoModel();
        
        // Definir período padrão (mês atual)
        $hoje = date('Y-m-d');
        $primeiroDia = date('Y-m-01');
        $ultimoDia = date('Y-m-t');
        
        // Aplicar filtros de período
        if ($relatorio['periodo'] === 'personalizado') {
            if (!empty($relatorio['data_inicio'])) {
                $primeiroDia = $relatorio['data_inicio'];
            }
            
            if (!empty($relatorio['data_fim'])) {
                $ultimoDia = $relatorio['data_fim'];
            }
        }
        
        // Obter dados agrupados por categoria
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT 
                c.id as categoria_id,
                c.nome as categoria_nome,
                c.icone as categoria_icone,
                c.tipo as categoria_tipo,
                COUNT(t.id) as quantidade_transacoes,
                SUM(t.valor) as valor_total
            FROM 
                categorias c
            LEFT JOIN 
                transacoes t ON c.id = t.categoria_id AND
                               t.data BETWEEN ? AND ? AND
                               t.usuario_id = ? AND
                               t.deleted_at IS NULL
            WHERE 
                c.deleted_at IS NULL
            GROUP BY 
                c.id
            ORDER BY 
                c.tipo ASC, valor_total DESC
        ", [$primeiroDia, $ultimoDia, $relatorio['usuario_id']]);
        
        $resultados = $query->getResultArray();
        
        // Formatação adicional
        foreach ($resultados as &$resultado) {
            $resultado['valor_formatado'] = 'R$ ' . number_format(abs($resultado['valor_total'] ?? 0), 2, ',', '.');
            
            // Calcular percentual em relação ao total por tipo (receita/despesa)
            $resultado['percentual'] = 0;
            $resultado['percentual_formatado'] = '0%';
        }
        
        // Calcular percentuais
        $totalReceitas = 0;
        $totalDespesas = 0;
        
        foreach ($resultados as $resultado) {
            if ($resultado['categoria_tipo'] === 'receita' && $resultado['valor_total'] > 0) {
                $totalReceitas += $resultado['valor_total'];
            } elseif ($resultado['categoria_tipo'] === 'despesa' && $resultado['valor_total'] < 0) {
                $totalDespesas += abs($resultado['valor_total']);
            }
        }
        
        foreach ($resultados as &$resultado) {
            if ($resultado['categoria_tipo'] === 'receita' && $totalReceitas > 0) {
                $resultado['percentual'] = ($resultado['valor_total'] / $totalReceitas) * 100;
            } elseif ($resultado['categoria_tipo'] === 'despesa' && $totalDespesas > 0) {
                $resultado['percentual'] = (abs($resultado['valor_total']) / $totalDespesas) * 100;
            }
            
            $resultado['percentual_formatado'] = number_format($resultado['percentual'], 2, ',', '.') . '%';
        }
        
        return $resultados;
    }
    
    private function getDadosCarteiras($relatorio)
    {
        $carteiraModel = new \App\Models\CarteiraModel();
        
        // Buscar carteiras ativas
        $carteiras = $carteiraModel->getCarteirasAtivas($relatorio['usuario_id']);
        
        // Formatação adicional
        foreach ($carteiras as &$carteira) {
            $carteira['saldo_formatado'] = 'R$ ' . number_format($carteira['saldo'], 2, ',', '.');
            
            // Converter tipo para formato legível
            $tipos = [
                'conta_corrente' => 'Conta Corrente',
                'conta_poupanca' => 'Conta Poupança',
                'dinheiro' => 'Dinheiro',
                'investimento' => 'Investimento',
                'cartao_credito' => 'Cartão de Crédito',
                'outros' => 'Outros'
            ];
            
            $carteira['tipo_formatado'] = $tipos[$carteira['tipo']] ?? $carteira['tipo'];
        }
        
        return $carteiras;
    }
    
    // Aplicar filtros de período nas consultas
    private function aplicarFiltrosPeriodo(&$builder, $relatorio)
    {
        // Definir período padrão (mês atual)
        $hoje = date('Y-m-d');
        
        switch ($relatorio['periodo']) {
            case 'dia':
                $builder->where('data', $hoje);
                break;
                
            case 'semana':
                $inicioDaSemana = date('Y-m-d', strtotime('monday this week'));
                $builder->where('data >=', $inicioDaSemana)
                        ->where('data <=', $hoje);
                break;
                
            case 'mes':
                $inicioDaMes = date('Y-m-01');
                $builder->where('data >=', $inicioDaMes)
                        ->where('data <=', $hoje);
                break;
                
            case 'ano':
                $inicioDoAno = date('Y-01-01');
                $builder->where('data >=', $inicioDoAno)
                        ->where('data <=', $hoje);
                break;
                
            case 'personalizado':
                if (!empty($relatorio['data_inicio'])) {
                    $builder->where('data >=', $relatorio['data_inicio']);
                }
                
                if (!empty($relatorio['data_fim'])) {
                    $builder->where('data <=', $relatorio['data_fim']);
                }
                break;
                
            default:
                // Padrão: mês atual
                $inicioDaMes = date('Y-m-01');
                $builder->where('data >=', $inicioDaMes)
                        ->where('data <=', $hoje);
                break;
        }
    }
    
    // Gerar resumo dos dados para o relatório
    private function gerarResumo($dados, $relatorio)
    {
        $resumo = [
            'quantidade' => count($dados),
            'totais' => []
        ];
        
        switch ($relatorio['tipo']) {
            case 'transacoes':
                $resumo['totais']['receitas'] = 0;
                $resumo['totais']['despesas'] = 0;
                $resumo['totais']['saldo'] = 0;
                
                foreach ($dados as $transacao) {
                    if ($transacao['categoria_tipo'] === 'receita') {
                        $resumo['totais']['receitas'] += $transacao['valor'];
                    } else {
                        $resumo['totais']['despesas'] += abs($transacao['valor']);
                    }
                }
                
                $resumo['totais']['saldo'] = $resumo['totais']['receitas'] - $resumo['totais']['despesas'];
                
                // Formatar valores
                $resumo['totais']['receitas_formatado'] = 'R$ ' . number_format($resumo['totais']['receitas'], 2, ',', '.');
                $resumo['totais']['despesas_formatado'] = 'R$ ' . number_format($resumo['totais']['despesas'], 2, ',', '.');
                $resumo['totais']['saldo_formatado'] = 'R$ ' . number_format($resumo['totais']['saldo'], 2, ',', '.');
                break;
                
            case 'orcamento':
                $resumo['totais']['limite_total'] = 0;
                $resumo['totais']['gasto_total'] = 0;
                $resumo['totais']['disponivel'] = 0;
                
                foreach ($dados as $orcamento) {
                    $resumo['totais']['limite_total'] += $orcamento['valor_limite'] ?? 0;
                    $resumo['totais']['gasto_total'] += $orcamento['valor_gasto'] ?? 0;
                }
                
                $resumo['totais']['disponivel'] = $resumo['totais']['limite_total'] - $resumo['totais']['gasto_total'];
                
                // Formatar valores
                $resumo['totais']['limite_total_formatado'] = 'R$ ' . number_format($resumo['totais']['limite_total'], 2, ',', '.');
                $resumo['totais']['gasto_total_formatado'] = 'R$ ' . number_format($resumo['totais']['gasto_total'], 2, ',', '.');
                $resumo['totais']['disponivel_formatado'] = 'R$ ' . number_format($resumo['totais']['disponivel'], 2, ',', '.');
                break;
                
            case 'contas':
                $resumo['totais']['a_pagar'] = 0;
                $resumo['totais']['a_receber'] = 0;
                $resumo['totais']['saldo'] = 0;
                
                foreach ($dados as $conta) {
                    if ($conta['tipo'] === 'pagar') {
                        $resumo['totais']['a_pagar'] += $conta['valor'];
                    } else {
                        $resumo['totais']['a_receber'] += $conta['valor'];
                    }
                }
                
                $resumo['totais']['saldo'] = $resumo['totais']['a_receber'] - $resumo['totais']['a_pagar'];
                
                // Formatar valores
                $resumo['totais']['a_pagar_formatado'] = 'R$ ' . number_format($resumo['totais']['a_pagar'], 2, ',', '.');
                $resumo['totais']['a_receber_formatado'] = 'R$ ' . number_format($resumo['totais']['a_receber'], 2, ',', '.');
                $resumo['totais']['saldo_formatado'] = 'R$ ' . number_format($resumo['totais']['saldo'], 2, ',', '.');
                break;
                
            case 'fluxo_caixa':
                $resumo['totais']['receitas'] = 0;
                $resumo['totais']['despesas'] = 0;
                $resumo['totais']['saldo'] = 0;
                
                foreach ($dados as $fluxo) {
                    $resumo['totais']['receitas'] += $fluxo['receitas'];
                    $resumo['totais']['despesas'] += abs($fluxo['despesas']);
                    $resumo['totais']['saldo'] += $fluxo['saldo'];
                }
                
                // Formatar valores
                $resumo['totais']['receitas_formatado'] = 'R$ ' . number_format($resumo['totais']['receitas'], 2, ',', '.');
                $resumo['totais']['despesas_formatado'] = 'R$ ' . number_format($resumo['totais']['despesas'], 2, ',', '.');
                $resumo['totais']['saldo_formatado'] = 'R$ ' . number_format($resumo['totais']['saldo'], 2, ',', '.');
                break;
                
            case 'categorias':
                $resumo['totais']['receitas'] = 0;
                $resumo['totais']['despesas'] = 0;
                $resumo['totais']['categorias_receita'] = 0;
                $resumo['totais']['categorias_despesa'] = 0;
                
                foreach ($dados as $categoria) {
                    if ($categoria['categoria_tipo'] === 'receita') {
                        $resumo['totais']['receitas'] += $categoria['valor_total'] ?? 0;
                        $resumo['totais']['categorias_receita']++;
                    } else {
                        $resumo['totais']['despesas'] += abs($categoria['valor_total'] ?? 0);
                        $resumo['totais']['categorias_despesa']++;
                    }
                }
                
                // Formatar valores
                $resumo['totais']['receitas_formatado'] = 'R$ ' . number_format($resumo['totais']['receitas'], 2, ',', '.');
                $resumo['totais']['despesas_formatado'] = 'R$ ' . number_format($resumo['totais']['despesas'], 2, ',', '.');
                break;
                
            case 'carteiras':
                $resumo['totais']['saldo_total'] = 0;
                $resumo['totais']['contas_bancarias'] = 0;
                $resumo['totais']['outros'] = 0;
                
                foreach ($dados as $carteira) {
                    $resumo['totais']['saldo_total'] += $carteira['saldo'];
                    
                    if (in_array($carteira['tipo'], ['conta_corrente', 'conta_poupanca'])) {
                        $resumo['totais']['contas_bancarias']++;
                    } else {
                        $resumo['totais']['outros']++;
                    }
                }
                
                // Formatar valores
                $resumo['totais']['saldo_total_formatado'] = 'R$ ' . number_format($resumo['totais']['saldo_total'], 2, ',', '.');
                break;
        }
        
        return $resumo;
    }
} 