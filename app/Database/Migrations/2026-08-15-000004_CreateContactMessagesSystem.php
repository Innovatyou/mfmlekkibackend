<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContactMessagesSystem extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('tbl_contact_messages')) {
            $this->forge->addField([
                'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'name'         => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'email'        => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'phone'        => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => ''],
                'subject'      => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'message'      => ['type' => 'TEXT', 'null' => true],
                'status'       => ['type' => 'ENUM', 'constraint' => ['unread', 'read', 'replied'], 'default' => 'unread'],
                'admin_reply'  => ['type' => 'TEXT', 'null' => true],
                'replied_at'   => ['type' => 'DATETIME', 'null' => true],
                'created_at'   => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->addKey('status');
            $this->forge->createTable('tbl_contact_messages');
        }

        $fields = $this->db->getFieldNames('tbl_landing_content');
        $columns = [
            'contact_form_title'        => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => 'Send Us a Message', 'after' => 'contact_map_embed'],
            'contact_form_subtitle'     => ['type' => 'VARCHAR', 'constraint' => 500, 'default' => "We'd love to hear from you — we'll get back to you soon.", 'after' => 'contact_form_title'],
            'contact_notification_email' => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => '', 'after' => 'contact_form_subtitle'],
            'show_contact_form'         => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'after' => 'show_contact'],
        ];
        foreach ($columns as $name => $def) {
            if (!in_array($name, $fields, true)) {
                $this->forge->addColumn('tbl_landing_content', [$name => $def]);
                $fields[] = $name;
            }
        }
    }

    public function down()
    {
        foreach (['show_contact_form', 'contact_notification_email', 'contact_form_subtitle', 'contact_form_title'] as $col) {
            if ($this->db->fieldExists($col, 'tbl_landing_content')) {
                $this->forge->dropColumn('tbl_landing_content', $col);
            }
        }
        $this->forge->dropTable('tbl_contact_messages', true);
    }
}
