<?php

namespace App\Models;

use CodeIgniter\Model;

class AssetSoftwareModel extends Model
{
    protected $table         = 'asset_softwares';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['asset_id', 'name', 'license_type', 'license_expiry', 'last_updated', 'is_updated', 'notes'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $returnType    = 'array';

    public function getByAssetId(int $assetId): array
    {
        return $this->where('asset_id', $assetId)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function syncSoftwares(int $assetId, array $softwares): array
    {
        $this->where('asset_id', $assetId)->delete();

        $insertedIds = [];

        foreach ($softwares as $software) {
            $name = trim((string) ($software['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $this->insert([
                'asset_id'        => $assetId,
                'name'            => $name,
                'license_type'    => trim((string) ($software['license_type'] ?? '')) ?: null,
                'license_expiry'  => trim((string) ($software['license_expiry'] ?? '')) ?: null,
                'last_updated'    => trim((string) ($software['last_updated'] ?? '')) ?: null,
                'is_updated'      => (int) ($software['is_updated'] ?? 0),
                'notes'           => trim((string) ($software['notes'] ?? '')) ?: null,
            ]);

            $insertedIds[] = (int) $this->getInsertID();
        }

        return $insertedIds;
    }
}
