<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionRoleAccessModel extends Model
{
    protected $table            = 'section_role_access';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['role_id', 'section_id', 'is_enabled', 'updated_at'];

    /**
     * Get all section IDs that are enabled for a given role.
     *
     * @return int[]
     */
    public function getEnabledSectionIds(int $roleId): array
    {
        $rows = $this->where('role_id', $roleId)
                     ->where('is_enabled', 1)
                     ->findAll();

        return array_map(fn($r) => (int) $r['section_id'], $rows);
    }

    /**
     * Get the full access matrix for the admin view.
     * Returns: [ role_id => [ section_id => is_enabled, ... ], ... ]
     */
    public function getAccessMatrix(array $roleIds): array
    {
        $rows = $this->whereIn('role_id', $roleIds)->findAll();

        $matrix = [];
        foreach ($rows as $row) {
            $matrix[(int) $row['role_id']][(int) $row['section_id']] = (int) $row['is_enabled'];
        }

        return $matrix;
    }

    /**
     * Upsert a single access entry.
     */
    public function setAccess(int $roleId, int $sectionId, bool $enabled): void
    {
        $existing = $this->where('role_id', $roleId)
                         ->where('section_id', $sectionId)
                         ->first();

        if ($existing) {
            $this->update($existing['id'], [
                'is_enabled' => $enabled ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->insert([
                'role_id'    => $roleId,
                'section_id' => $sectionId,
                'is_enabled' => $enabled ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
