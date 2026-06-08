<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRolesAndPermissions extends Migration
{
    public function up()
    {
        // Create roles table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'display_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', false, true);
        $this->forge->createTable('tbl_roles');

        // Create permissions table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'display_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'module' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'comment'    => 'Module name (e.g., users, articles, videos)',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', false, true);
        $this->forge->createTable('tbl_permissions');

        // Create role_permissions junction table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'role_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'permission_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', false, true);
        $this->forge->addForeignKey('role_id', 'tbl_roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('permission_id', 'tbl_permissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tbl_role_permissions');

        // Add role_id column to tbl_churches if it doesn't exist
        if (!$this->db->fieldExists('role_id', 'tbl_churches')) {
            $this->forge->addColumn('tbl_churches', [
                'role_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'role',
                    'comment'    => 'Foreign key to tbl_roles',
                ],
            ]);
        }
    }

    public function down()
    {
        // Drop foreign key first
        $this->forge->dropForeignKey('tbl_role_permissions', 'tbl_role_permissions_role_id_foreign');
        $this->forge->dropForeignKey('tbl_role_permissions', 'tbl_role_permissions_permission_id_foreign');
        
        // Drop tables
        $this->forge->dropTable('tbl_role_permissions', true);
        $this->forge->dropTable('tbl_permissions', true);
        $this->forge->dropTable('tbl_roles', true);
        
        // Drop column from tbl_churches
        if ($this->db->fieldExists('role_id', 'tbl_churches')) {
            $this->forge->dropColumn('tbl_churches', 'role_id');
        }
    }
}
