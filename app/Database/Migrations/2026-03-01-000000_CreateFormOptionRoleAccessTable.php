<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFormOptionRoleAccessTable extends Migration
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
            'option_type' => [
                'type'       => 'ENUM',
                'constraint' => ['request_type', 'request_platform', 'request_action', 'equipment'],
                'comment'    => 'Which form-option table this row governs',
            ],
            'option_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'comment'    => 'PK of the governed row in its source table',
            ],
            'role_id' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'comment'    => 'References UserRole enum value (5=Employee, 6=Student)',
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
        $this->forge->addUniqueKey(['option_type', 'option_id', 'role_id']);
        $this->forge->createTable('form_option_role_access');

        // Seed default rows: enable everything for Employee (5) and Student (6)
        $db = \Config\Database::connect();

        $maps = [
            'request_type'     => ['table' => 'request_types',     'pk' => 'request_type_id'],
            'request_platform' => ['table' => 'request_platforms', 'pk' => 'platform_id'],
            'request_action'   => ['table' => 'request_actions',   'pk' => 'action_id'],
            'equipment'        => ['table' => 'ticket_equipments', 'pk' => 'equipment_id'],
        ];

        $rows = [];
        foreach ($maps as $optionType => $meta) {
            $items = $db->table($meta['table'])->get()->getResultArray();
            foreach ([5, 6] as $roleId) {
                foreach ($items as $item) {
                    $rows[] = [
                        'option_type' => $optionType,
                        'option_id'   => $item[$meta['pk']],
                        'role_id'     => $roleId,
                        'is_enabled'  => 1,
                        'updated_at'  => date('Y-m-d H:i:s'),
                    ];
                }
            }
        }

        if (! empty($rows)) {
            $db->table('form_option_role_access')->insertBatch($rows);
        }
    }

    public function down()
    {
        $this->forge->dropTable('form_option_role_access');
    }
}
