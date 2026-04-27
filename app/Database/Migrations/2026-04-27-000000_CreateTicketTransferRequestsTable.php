<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketTransferRequestsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'transfer_request_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'job_ticket_response_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'job_ticket_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'requested_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'suggested_staff_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'pending',
            ],
            'reviewed_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'reviewed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'review_note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'approved_staff_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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

        $this->forge->addKey('transfer_request_id', true);
        $this->forge->addKey('job_ticket_response_id');
        $this->forge->addKey('job_ticket_id');
        $this->forge->addKey('requested_by');
        $this->forge->addKey('status');

        $this->forge->addForeignKey('job_ticket_response_id', 'job_ticket_responses', 'job_ticket_response_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('job_ticket_id', 'job_tickets', 'job_ticket_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('requested_by', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('suggested_staff_id', 'users', 'user_id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('reviewed_by', 'users', 'user_id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('approved_staff_id', 'users', 'user_id', 'SET NULL', 'SET NULL');

        $this->forge->createTable('ticket_transfer_requests');
    }

    public function down()
    {
        $this->forge->dropTable('ticket_transfer_requests', true);
    }
}
