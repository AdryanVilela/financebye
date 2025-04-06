<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUltimoAcessoToUsuarios extends Migration
{
    public function up()
    {
        // Adicionar campo de último acesso se não existir
        if (!$this->db->fieldExists('ultimo_acesso', 'usuarios')) {
            $this->forge->addColumn('usuarios', [
                'ultimo_acesso' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'created_at'
                ],
            ]);
        }
        
        // Adicionar campo de status (para complementar o ativo)
        if (!$this->db->fieldExists('status', 'usuarios')) {
            $this->forge->addColumn('usuarios', [
                'status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'default' => 'ativo',
                    'after' => 'ativo'
                ],
            ]);
        }
    }

    public function down()
    {
        // Remover os campos adicionados
        if ($this->db->fieldExists('ultimo_acesso', 'usuarios')) {
            $this->forge->dropColumn('usuarios', 'ultimo_acesso');
        }
        
        if ($this->db->fieldExists('status', 'usuarios')) {
            $this->forge->dropColumn('usuarios', 'status');
        }
    }
} 