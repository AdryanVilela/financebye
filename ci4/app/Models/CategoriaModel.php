<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table      = 'categorias';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['empresa_id', 'nome', 'tipo'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'empresa_id' => 'required|is_natural_no_zero',
        'nome'     => 'required|min_length[2]|max_length[100]',
        'tipo'     => 'required|in_list[receita,despesa]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    /**
     * Filtra categorias por empresa
     */
    public function porEmpresa($empresaId)
    {
        return $this->where('empresa_id', $empresaId);
    }

    /**
     * Filtra categorias por tipo
     */
    public function porTipo($tipo)
    {
        return $this->where('tipo', $tipo);
    }

    /**
     * Busca IDs de categorias por tipo
     */
    public function buscarIdsPorTipo($tipo, $empresaId)
    {
        $categorias = $this->where('tipo', $tipo)
                           ->where('empresa_id', $empresaId)
                           ->findAll();
        
        return array_column($categorias, 'id');
    }
} 