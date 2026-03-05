<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResponsePartsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'job_ticket_response_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'part_type' => [
                'type'       => 'ENUM',
                'constraint' => ['replaced', 'used'],
                'default'    => 'used',
            ],
            'part_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'quantity' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 1,
            ],
            'unit_cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
                'default'    => null,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('job_ticket_response_id');
        $this->forge->addForeignKey('job_ticket_response_id', 'job_ticket_responses', 'job_ticket_response_id', 'CASCADE', 'CASCADE', 'fk_response_parts_response');
        $this->forge->createTable('response_parts', true);
    }

    public function down()
    {
        $this->forge->dropTable('response_parts', true);
    }
}
