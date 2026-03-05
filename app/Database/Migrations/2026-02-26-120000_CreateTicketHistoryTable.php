<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketHistoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'history_id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'job_ticket_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'e.g. created, assigned, in_progress, completed, verified, closed, cancelled, transferred',
            ],
            'old_status' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'Previous job_status value',
            ],
            'new_status' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'New job_status value',
            ],
            'performed_by' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'user_id who performed this action',
            ],
            'remarks' => [
                'type'       => 'TEXT',
                'null'       => true,
                'comment'    => 'Extra details like staff name, transfer info',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('history_id', true);
        $this->forge->addKey('job_ticket_id');
        $this->forge->addKey('action');
        $this->forge->createTable('ticket_history', true);
    }

    public function down()
    {
        $this->forge->dropTable('ticket_history', true);
    }
}
