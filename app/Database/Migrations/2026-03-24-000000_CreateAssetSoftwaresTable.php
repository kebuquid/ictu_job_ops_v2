<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssetSoftwaresTable extends Migration
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
            'asset_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'license_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'license_expiry' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'last_updated' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'is_updated' => [
                'type'    => 'TINYINT',
                'default' => 0,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('asset_id');
        $this->forge->addForeignKey('asset_id', 'assets', 'asset_id', 'CASCADE', 'CASCADE', 'fk_asset_softwares_asset');
        $this->forge->createTable('asset_softwares', true);

        if ($this->db->fieldExists('software_list', 'assets')) {
            $assets = $this->db->table('assets')
                ->select('asset_id, software_list')
                ->where('software_list IS NOT NULL')
                ->get()
                ->getResultArray();

            $rows = [];
            foreach ($assets as $asset) {
                $decoded = json_decode((string) $asset['software_list'], true);
                if (! is_array($decoded)) {
                    continue;
                }

                foreach ($decoded as $software) {
                    $name = trim((string) ($software['name'] ?? ''));
                    if ($name === '') {
                        continue;
                    }

                    $rows[] = [
                        'asset_id'       => (int) $asset['asset_id'],
                        'name'           => $name,
                        'license_type'   => trim((string) ($software['license_type'] ?? '')) ?: null,
                        'license_expiry' => trim((string) ($software['license_expiry'] ?? '')) ?: null,
                        'last_updated'   => trim((string) ($software['last_updated'] ?? '')) ?: null,
                        'is_updated'     => (int) ($software['is_updated'] ?? 0),
                        'notes'          => trim((string) ($software['notes'] ?? '')) ?: null,
                    ];
                }
            }

            if ($rows !== []) {
                $this->db->table('asset_softwares')->insertBatch($rows);
            }

            $this->forge->dropColumn('assets', 'software_list');
        }
    }

    public function down()
    {
        if (! $this->db->fieldExists('software_list', 'assets')) {
            $this->forge->addColumn('assets', [
                'software_list' => [
                    'type' => 'LONGTEXT',
                    'null' => true,
                ],
            ]);
        }

        if ($this->db->tableExists('asset_softwares')) {
            $softwares = $this->db->table('asset_softwares')
                ->select('asset_id, name, license_type, license_expiry, last_updated, is_updated, notes')
                ->orderBy('asset_id', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()
                ->getResultArray();

            if ($softwares !== []) {
                $grouped = [];
                foreach ($softwares as $software) {
                    $assetId = (int) $software['asset_id'];
                    $grouped[$assetId][] = [
                        'name'           => $software['name'],
                        'license_type'   => $software['license_type'],
                        'license_expiry' => $software['license_expiry'],
                        'last_updated'   => $software['last_updated'],
                        'is_updated'     => (string) ((int) ($software['is_updated'] ?? 0)),
                        'notes'          => $software['notes'],
                    ];
                }

                foreach ($grouped as $assetId => $list) {
                    $this->db->table('assets')
                        ->where('asset_id', $assetId)
                        ->update(['software_list' => json_encode($list)]);
                }
            }

            $this->forge->dropTable('asset_softwares', true);
        }
    }
}
