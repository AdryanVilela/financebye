<?php

namespace App\Models;

use CodeIgniter\Model;

class ClienteModel extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nome',
        'email',
        'telefone',
        'documento',
        'endereco',
        'observacoes',
        'usuario_id',
        'empresa_id',
        'created_at',
        'updated_at'
    ];
    
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    /**
     * Busca clientes por nome, email ou documento
     */
    public function buscarPorTermos($termo, $usuario_id, $empresa_id)
    {
        return $this->where('usuario_id', $usuario_id)
                   ->where('empresa_id', $empresa_id)
                   ->groupStart()
                       ->like('nome', $termo)
                       ->orLike('email', $termo)
                       ->orLike('documento', $termo)
                   ->groupEnd()
                   ->orderBy('nome', 'ASC')
                   ->findAll();
    }
} 