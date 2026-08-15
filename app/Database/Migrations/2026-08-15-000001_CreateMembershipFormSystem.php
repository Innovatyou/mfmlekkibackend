<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMembershipFormSystem extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('tbl_membership_form_fields')) {
            $this->forge->addField([
                'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'field_key'   => ['type' => 'VARCHAR', 'constraint' => 100],
                'label'       => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'field_type'  => ['type' => 'ENUM', 'constraint' => ['text', 'email', 'tel', 'textarea', 'date', 'select', 'radio', 'checkbox'], 'default' => 'text'],
                'options'     => ['type' => 'TEXT', 'null' => true],
                'placeholder' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'help_text'   => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
                'required'    => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'is_core'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
                'sort_order'  => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
                'status'      => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
                'created_at'  => ['type' => 'DATETIME', 'null' => true],
                'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->addUniqueKey('field_key');
            $this->forge->createTable('tbl_membership_form_fields');

            $now = date('Y-m-d H:i:s');

            // Core fields — map directly onto existing tbl_members columns.
            // field_key is locked (used by LandingApi::join() to route the value
            // onto the matching tbl_members column); label/required/help/order
            // stay admin-editable.
            $core = [
                ['firstname',   'First Name',     'text',  null, 'e.g. John', 1, 1],
                ['lastname',    'Last Name',      'text',  null, 'e.g. Doe', 1, 2],
                ['email',       'Email Address',  'email', null, 'e.g. john@email.com', 1, 3],
                ['phonenumber', 'Phone Number',   'tel',   null, 'e.g. +1 234 567 8900', 1, 4],
                ['gender',      'Gender',         'radio', json_encode(['Male', 'Female']), null, 1, 5],
                ['dob',         'Date of Birth',  'date',  null, null, 1, 6],
                ['address',     'Address',        'text',  null, 'e.g. 123 Church Street, City', 0, 7],
            ];
            foreach ($core as [$key, $label, $type, $options, $placeholder, $required, $order]) {
                $this->db->table('tbl_membership_form_fields')->insert([
                    'field_key'   => $key,
                    'label'       => $label,
                    'field_type'  => $type,
                    'options'     => $options,
                    'placeholder' => $placeholder,
                    'help_text'   => $key === 'dob' ? "We ask so we can celebrate your birthday with you." : null,
                    'required'    => $required,
                    'is_core'     => 1,
                    'sort_order'  => $order,
                    'status'      => 'active',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }

            // Custom fields — freely editable/removable by the admin, answers
            // stored in tbl_membership_field_answers.
            $custom = [
                ['marital_status', 'Marital Status', 'radio', ['Single', 'Married', 'Divorced', 'Widowed'], null, 0, 8],
                ['occupation', 'Occupation', 'text', null, 'e.g. Software Engineer, Business Owner, Student', 0, 9],
                ['how_heard', 'How Did You Hear About Us?', 'select', ['Friend or Family', 'Social Media', 'Church Website', 'An Event', 'Just Walked In', 'Other'], null, 0, 10],
                ['ministry_interest', 'Which Ministries Interest You?', 'checkbox', ['Choir / Music', 'Ushering', 'Media & Technical', "Children's Ministry", 'Outreach & Missions', 'Prayer Team', 'Hospitality', 'Not Sure Yet'], null, 0, 11],
                ['prayer_request', 'Prayer Request or Additional Notes', 'textarea', null, 'Anything you would like us to know or pray with you about', 0, 12],
            ];
            foreach ($custom as [$key, $label, $type, $options, $placeholder, $required, $order]) {
                $this->db->table('tbl_membership_form_fields')->insert([
                    'field_key'   => $key,
                    'label'       => $label,
                    'field_type'  => $type,
                    'options'     => $options ? json_encode($options) : null,
                    'placeholder' => $placeholder,
                    'help_text'   => null,
                    'required'    => $required,
                    'is_core'     => 0,
                    'sort_order'  => $order,
                    'status'      => 'active',
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
            }
        }

        if (!$this->db->tableExists('tbl_membership_field_answers')) {
            $this->forge->addField([
                'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'member_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
                'field_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
                'field_key'  => ['type' => 'VARCHAR', 'constraint' => 100],
                'label'      => ['type' => 'VARCHAR', 'constraint' => 255, 'default' => ''],
                'value'      => ['type' => 'TEXT', 'null' => true],
                'created_at' => ['type' => 'DATETIME', 'null' => true],
            ]);
            $this->forge->addKey('id', false, true);
            $this->forge->addKey('member_id');
            $this->forge->createTable('tbl_membership_field_answers');
        }
    }

    public function down()
    {
        $this->forge->dropTable('tbl_membership_field_answers', true);
        $this->forge->dropTable('tbl_membership_form_fields', true);
    }
}
