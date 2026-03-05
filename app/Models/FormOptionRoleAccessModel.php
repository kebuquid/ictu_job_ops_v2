<?php

namespace App\Models;

use CodeIgniter\Model;

class FormOptionRoleAccessModel extends Model
{
    protected $table            = 'form_option_role_access';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['option_type', 'option_id', 'role_id', 'is_enabled', 'updated_at'];

    // ──────────────────────────────────────────────
    //  Queries used by TicketController
    // ──────────────────────────────────────────────

    /**
     * Get all enabled option IDs for a given type and role.
     *
     * @param  string $optionType  e.g. 'request_type', 'request_platform', 'request_action', 'equipment'
     * @param  int    $roleId
     * @return int[]
     */
    public function getEnabledOptionIds(string $optionType, int $roleId): array
    {
        $rows = $this->where('option_type', $optionType)
                     ->where('role_id', $roleId)
                     ->where('is_enabled', 1)
                     ->findAll();

        return array_map(fn($r) => (int) $r['option_id'], $rows);
    }

    // ──────────────────────────────────────────────
    //  Queries used by SuperAdmin view
    // ──────────────────────────────────────────────

    /**
     * Build access matrix for a specific option type.
     * Returns: [ role_id => [ option_id => is_enabled, … ], … ]
     */
    public function getAccessMatrix(string $optionType, array $roleIds): array
    {
        $rows = $this->where('option_type', $optionType)
                     ->whereIn('role_id', $roleIds)
                     ->findAll();

        $matrix = [];
        foreach ($rows as $row) {
            $matrix[(int) $row['role_id']][(int) $row['option_id']] = (int) $row['is_enabled'];
        }

        return $matrix;
    }

    /**
     * Upsert a single access entry.
     */
    public function setAccess(string $optionType, int $optionId, int $roleId, bool $enabled): void
    {
        $existing = $this->where('option_type', $optionType)
                         ->where('option_id', $optionId)
                         ->where('role_id', $roleId)
                         ->first();

        if ($existing) {
            $this->update($existing['id'], [
                'is_enabled' => $enabled ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->insert([
                'option_type' => $optionType,
                'option_id'   => $optionId,
                'role_id'     => $roleId,
                'is_enabled'  => $enabled ? 1 : 0,
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
