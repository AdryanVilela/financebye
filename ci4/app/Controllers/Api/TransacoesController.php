<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;

class TransacoesController extends BaseController
{
    public function index()
    {
        // Log para debug
        log_message('info', 'API Transações: Iniciando consulta de transações');
        
        try {
            // Carregar o modelo
            $transacoesModel = new \App\Models\TransacoesModel();
            
            // Obter usuário logado
            $userId = session()->get('usuario_id');
            if (!$userId) {
                log_message('error', 'API Transações: Usuário não autenticado');
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Usuário não autenticado'
                ]);
            }
            
            // Aplicar filtros de período
            $periodo = $this->request->getGet('periodo');
            $dataInicio = $this->request->getGet('data_inicio');
            $dataFim = $this->request->getGet('data_fim');
            $categoriaId = $this->request->getGet('categoria_id');
            $tipo = $this->request->getGet('tipo');
            $limit = $this->request->getGet('limit');
            
            // Converter limit para inteiro
            $limitInt = $limit ? (int)$limit : null;
            
            // Log para debug
            log_message('info', "API Transações: Filtros aplicados - " . 
                "periodo: $periodo, dataInicio: $dataInicio, dataFim: $dataFim, " . 
                "categoriaId: $categoriaId, tipo: $tipo, limit: $limitInt");
            
            $builder = $transacoesModel->builder()
                ->select('transacoes.*, categorias.nome as categoria_nome, categorias.tipo as categoria_tipo')
                ->join('categorias', 'categorias.id = transacoes.categoria_id')
                ->where('transacoes.usuario_id', $userId)
                ->orderBy('transacoes.data', 'DESC');
            
            // Aplicar filtros de categoria
            if (!empty($categoriaId)) {
                $builder->where('transacoes.categoria_id', $categoriaId);
                log_message('info', "API Transações: Filtrando por categoria_id: $categoriaId");
            }
            
            // Aplicar filtros de tipo (receita/despesa)
            if (!empty($tipo)) {
                $builder->where('categorias.tipo', $tipo);
                log_message('info', "API Transações: Filtrando por tipo: $tipo");
            }
            
            // Aplicar filtros de data conforme o período selecionado
            if (!empty($dataInicio) && !empty($dataFim)) {
                // Validar formato das datas
                $dataInicioObj = \DateTime::createFromFormat('Y-m-d', $dataInicio);
                $dataFimObj = \DateTime::createFromFormat('Y-m-d', $dataFim);
                
                if ($dataInicioObj && $dataFimObj) {
                    // Período personalizado
                    $builder->where('transacoes.data >=', $dataInicio);
                    $builder->where('transacoes.data <=', $dataFim);
                    log_message('info', "API Transações: Filtrando por período personalizado de $dataInicio até $dataFim");
                } else {
                    log_message('warning', "API Transações: Datas inválidas: data_inicio=$dataInicio, data_fim=$dataFim");
                }
            } else if ($periodo) {
                // Calcular datas com base no período
                $hoje = date('Y-m-d');
                $dataFimPeriodo = $hoje;
                $dataInicioPeriodo = null;
                
                switch ($periodo) {
                    case 'mes':
                        $dataInicioPeriodo = date('Y-m-01'); // Primeiro dia do mês atual
                        log_message('info', "API Transações: Filtrando por mês atual");
                        break;
                    case 'trimestre':
                        $dataInicioPeriodo = date('Y-m-d', strtotime('-3 months', strtotime($hoje)));
                        log_message('info', "API Transações: Filtrando por último trimestre");
                        break;
                    case 'ano':
                        $dataInicioPeriodo = date('Y-01-01'); // Primeiro dia do ano atual
                        log_message('info', "API Transações: Filtrando por ano atual");
                        break;
                    default:
                        // Padrão: mês atual
                        $dataInicioPeriodo = date('Y-m-01');
                        log_message('info', "API Transações: Período desconhecido, usando mês atual");
                        break;
                }
                
                if ($dataInicioPeriodo) {
                    $builder->where('transacoes.data >=', $dataInicioPeriodo);
                    $builder->where('transacoes.data <=', $dataFimPeriodo);
                    log_message('info', "API Transações: Filtrando por período de $dataInicioPeriodo até $dataFimPeriodo");
                }
            }
            
            // Limitar resultados se solicitado
            if ($limitInt > 0) {
                $builder->limit($limitInt);
                log_message('info', "API Transações: Limitando a $limitInt resultados");
            }
            
            // Executar consulta
            $sql = $builder->getCompiledSelect();
            log_message('info', "API Transações: SQL gerado: $sql");
            
            $transacoes = $builder->get()->getResultArray();
            
            // Log para debug
            log_message('info', 'API Transações: Encontradas ' . count($transacoes) . ' transações');
            
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $transacoes,
                'count' => count($transacoes),
                'filters' => [
                    'periodo' => $periodo,
                    'data_inicio' => $dataInicio,
                    'data_fim' => $dataFim,
                    'categoria_id' => $categoriaId,
                    'tipo' => $tipo,
                    'limit' => $limitInt
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'API Transações: Erro ao buscar transações: ' . $e->getMessage());
            log_message('error', 'API Transações: Stack trace: ' . $e->getTraceAsString());
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Erro ao buscar transações: ' . $e->getMessage()
            ]);
        }
    }
} 