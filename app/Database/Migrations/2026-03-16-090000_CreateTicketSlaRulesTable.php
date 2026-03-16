<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketSlaRulesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'sla_rule_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'section_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'request_type_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'platform_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'action_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'equipment_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'target_hours' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 24,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 1,
            ],
            'notes' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
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

        $this->forge->addPrimaryKey('sla_rule_id');
        $this->forge->addKey('section_id', false, false, 'idx_sla_section');
        $this->forge->addKey(['section_id', 'is_active'], false, false, 'idx_sla_section_active');

        $this->forge->addForeignKey('section_id', 'sections', 'section_id', 'CASCADE', 'CASCADE', 'fk_sla_section');
        $this->forge->addForeignKey('request_type_id', 'request_types', 'request_type_id', 'SET NULL', 'CASCADE', 'fk_sla_request_type');
        $this->forge->addForeignKey('platform_id', 'request_platforms', 'platform_id', 'SET NULL', 'CASCADE', 'fk_sla_platform');
        $this->forge->addForeignKey('action_id', 'request_actions', 'action_id', 'SET NULL', 'CASCADE', 'fk_sla_action');
        $this->forge->addForeignKey('equipment_id', 'ticket_equipments', 'equipment_id', 'SET NULL', 'CASCADE', 'fk_sla_equipment');

        $this->forge->createTable('ticket_sla_rules', true);
    }

    public function down()
    {
        $this->forge->dropTable('ticket_sla_rules', true);
    }
}
