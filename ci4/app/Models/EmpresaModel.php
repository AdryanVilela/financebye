<?php

namespace App\Models;

use CodeIgniter\Model;

class EmpresaModel extends Model
{
    protected $table      = 'empresas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['nome', 'cnpj', 'email', 'telefone'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nome'     => 'required|min_length[3]|max_length[100]',
        'cnpj'     => 'permit_empty|max_length[20]',
        'email'    => 'permit_empty|valid_email|max_length[100]',
        'telefone' => 'permit_empty|max_length[20]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
} 