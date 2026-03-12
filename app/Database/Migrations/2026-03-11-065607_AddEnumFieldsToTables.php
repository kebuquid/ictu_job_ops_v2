<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEnumFieldsToTables extends Migration
{
    public function up()
    {
        // Add dot_color and activity_label to job_status
        $this->forge->addColumn('job_status', [
            'dot_color' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'default'    => '',
                'after'      => 'color',
            ],
            'activity_label' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
                'default'    => '',
                'after'      => 'dot_color',
            ],
        ]);

        // Populate dot_color and activity_label for existing job_status rows
        $this->db->query("UPDATE job_status SET dot_color = CONCAT('bg-', color, '-500')");
        $this->db->query("UPDATE job_status SET activity_label = CASE status_id
            WHEN 1 THEN 'opened'
            WHEN 2 THEN 'moved to In Progress'
            WHEN 3 THEN 'waiting for parts'
            WHEN 4 THEN 'marked Completed'
            WHEN 5 THEN 'was Closed'
            WHEN 6 THEN 'was Cancelled'
            END");

        // Populate section colors
        $this->db->query("UPDATE sections SET color = 'blue' WHERE acronym = 'MIS'");
        $this->db->query("UPDATE sections SET color = 'green' WHERE acronym = 'NICM'");
        $this->db->query("UPDATE sections SET color = 'yellow' WHERE acronym = 'ICTRAM'");
    }

    public function down()
    {
        $this->forge->dropColumn('job_status', ['dot_color', 'activity_label']);
        $this->db->query("UPDATE sections SET color = ''");
    }
}
