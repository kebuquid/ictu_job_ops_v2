<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRequestorNameToJobTickets extends Migration
{
    public function up()
    {
        $this->forge->addColumn('job_tickets', [
            'requestor_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'requestor_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('job_tickets', 'requestor_name');
    }
}
