<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContasPagar extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'descricao' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'valor' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'data_emissao' => [
                'type'       => 'DATE',
                'null'       => true,
            ],
            'data_vencimento' => [
                'type'       => 'DATE',
                'null'       => true,
            ],
            'data_pagamento' => [
                'type'       => 'DATE',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pendente', 'concluido', 'atrasado', 'cancelado'],
                'default'    => 'pendente',
            ],
            'fornecedor_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'categoria_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'observacoes' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'usuario_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('fornecedor_id', 'fornecedores', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('categoria_id', 'categorias', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('contas_pagar');
    }

    public function down()
    {
        $this->forge->dropTable('contas_pagar', true);
    }
} 