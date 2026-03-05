<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserExpertiseTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'expertise_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['user_id', 'expertise_id']);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('expertise_id', 'expertise', 'expertise_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_expertise');
    }

    public function down()
    {
        $this->forge->dropTable('user_expertise');
    }
}
