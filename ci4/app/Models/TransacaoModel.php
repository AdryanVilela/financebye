<?php

namespace App\Models;

use CodeIgniter\Model;

class TransacaoModel extends Model
{
    protected $table      = 'transacoes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['empresa_id', 'categoria_id', 'usuario_id', 'descricao', 'valor', 'data'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'empresa_id'   => 'required|is_natural_no_zero',
        'categoria_id' => 'required|is_natural_no_zero',
        'usuario_id'   => 'required|is_natural_no_zero',
        'descricao'    => 'required|min_length[3]|max_length[255]',
        'valor'        => 'required|numeric',
        'data'         => 'required|valid_date',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    /**
     * Filtra transações por empresa
     */
    public function porEmpresa($empresaId)
    {
        return $this->where('empresa_id', $empresaId);
    }

    /**
     * Busca transações com informações de categoria
     */
    public function comCategoria()
    {
        return $this->select('transacoes.*, categorias.nome as categoria_nome, categorias.tipo as categoria_tipo')
                    ->join('categorias', 'categorias.id = transacoes.categoria_id');
    }

    /**
     * Filtra transações por tipo (receita/despesa)
     */
    public function porTipo($tipo, $empresaId)
    {
        $categoriaModel = new CategoriaModel();
        $categoriasIds = $categoriaModel->buscarIdsPorTipo($tipo, $empresaId);
        
        if (empty($categoriasIds)) {
            return $this->where('id', 0); // Retorna consulta vazia se não houver categorias
        }
        
        return $this->whereIn('categoria_id', $categoriasIds)
                    ->where('empresa_id', $empresaId);
    }

    /**
     * Calcula o total de receitas
     */
    public function totalReceitas($empresaId, $periodo = null)
    {
        $query = $this->porTipo('receita', $empresaId);
        
        if ($periodo) {
            if (isset($periodo['inicio'])) {
                $query->where('data >=', $periodo['inicio']);
            }
            if (isset($periodo['fim'])) {
                $query->where('data <=', $periodo['fim']);
            }
        }
        
        $result = $query->selectSum('valor')->first();
        return $result['valor'] ?? 0;
    }

    /**
     * Calcula o total de despesas
     */
    public function totalDespesas($empresaId, $periodo = null)
    {
        $query = $this->porTipo('despesa', $empresaId);
        
        if ($periodo) {
            if (isset($periodo['inicio'])) {
                $query->where('data >=', $periodo['inicio']);
            }
            if (isset($periodo['fim'])) {
                $query->where('data <=', $periodo['fim']);
            }
        }
        
        $result = $query->selectSum('valor')->first();
        return abs($result['valor'] ?? 0); // Retorna valor positivo
    }

    /**
     * Calcula o saldo (receitas - despesas)
     */
    public function saldo($empresaId, $periodo = null)
    {
        return $this->totalReceitas($empresaId, $periodo) - $this->totalDespesas($empresaId, $periodo);
    }
} 