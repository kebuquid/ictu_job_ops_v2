<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSectionRoleAccessTable extends Migration
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
            'role_id' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'comment'    => 'References UserRole enum value',
            ],
            'section_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'is_enabled' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 1,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['role_id', 'section_id']);
        $this->forge->addForeignKey('section_id', 'sections', 'section_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('section_role_access');

        // Seed default rows: enable all sections for Employee (5) and Student (6)
        $db       = \Config\Database::connect();
        $sections = $db->table('sections')->get()->getResultArray();

        $rows = [];
        foreach ([5, 6] as $roleId) {
            foreach ($sections as $s) {
                $rows[] = [
                    'role_id'    => $roleId,
                    'section_id' => $s['section_id'],
                    'is_enabled' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
        }

        if (!empty($rows)) {
            $db->table('section_role_access')->insertBatch($rows);
        }
    }

    public function down()
    {
        $this->forge->dropTable('section_role_access');
    }
}
