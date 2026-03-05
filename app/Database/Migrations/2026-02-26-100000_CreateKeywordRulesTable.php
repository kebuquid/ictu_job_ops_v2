<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKeywordRulesTable extends Migration
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
            'section_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
            ],
            'keyword' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'tip_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'tip_body' => [
                'type' => 'TEXT',
                'null' => true,
                'default' => null,
            ],
            'is_default' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 1,
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('section_id');
        $this->forge->addForeignKey('section_id', 'sections', 'section_id', 'CASCADE', 'CASCADE', 'kw_section_fk');
        $this->forge->createTable('keyword_rules', true);
    }

    public function down()
    {
        $this->forge->dropTable('keyword_rules', true);
    }
}
