<?php

namespace App\Models;

use CodeIgniter\Model;

class MaintenanceModel extends Model
{
    protected $table            = 'asset_maintenance';
    protected $primaryKey       = 'maintenance_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'group_id',
        'asset_id',
        'job_ticket_id',
        'equipment_type',
        'frequency',
        'activities',
        'conducted_by',
        'conducted_date',
        'verified_by',
        'verified_date',
        'remarks',
        'issue_description',
        'action_taken',
        'parts_replaced',
        'maintenance_date',
        'technician_id',
        'cost',
        'corrective_action',
        'corrective_date',
        'responsible_person',
        'responsible_date',
        'responsible_remarks',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Paginated list joined with assets and groups.
     */
    public function withAssetPaginated(int $perPage = 10, string $bld = '', string $unit = '', int $month = 0, int $year = 0): array
    {
        $q = $this->select('asset_maintenance.*, assets.asset_tag, assets.brand_model, assets.serial_number, asset_groups.group_name, asset_groups.group_code, COALESCE(ou.name, \'—\') AS unit_name, COALESCE(b.name, \'—\') AS building_name')
                    ->join('assets', 'assets.asset_id = asset_maintenance.asset_id', 'left')
                    ->join('asset_groups', 'asset_groups.group_id = asset_maintenance.group_id', 'left')
                    ->join('organizational_units ou', 'ou.unit_id = asset_groups.assigned_unit_id', 'left')
                    ->join('buildings b', 'b.building_id = ou.building_id', 'left');
        if ($bld   !== '') $q->where("COALESCE(b.name,  '—')", $bld);
        if ($unit  !== '') $q->where("COALESCE(ou.name, '—')", $unit);
        if ($month > 0)    $q->where('MONTH(asset_maintenance.maintenance_date)', $month);
        if ($year  > 0)    $q->where('YEAR(asset_maintenance.maintenance_date)',  $year);
        return $q->orderBy('asset_maintenance.maintenance_date', 'DESC')
                 ->paginate($perPage, 'default');
    }

    /**
     * Paginated search across key fields.
     */
    public function searchPaginated(string $keyword, int $perPage = 10, string $bld = '', string $unit = '', int $month = 0, int $year = 0): array
    {
        $q = $this->select('asset_maintenance.*, assets.asset_tag, assets.brand_model, assets.serial_number, asset_groups.group_name, asset_groups.group_code, COALESCE(ou.name, \'—\') AS unit_name, COALESCE(b.name, \'—\') AS building_name')
                    ->join('assets', 'assets.asset_id = asset_maintenance.asset_id', 'left')
                    ->join('asset_groups', 'asset_groups.group_id = asset_maintenance.group_id', 'left')
                    ->join('organizational_units ou', 'ou.unit_id = asset_groups.assigned_unit_id', 'left')
                    ->join('buildings b', 'b.building_id = ou.building_id', 'left')
                    ->groupStart()
                        ->like('assets.asset_tag', $keyword)
                        ->orLike('assets.brand_model', $keyword)
                        ->orLike('assets.serial_number', $keyword)
                        ->orLike('asset_groups.group_name', $keyword)
                        ->orLike('asset_maintenance.conducted_by', $keyword)
                        ->orLike('asset_maintenance.frequency', $keyword)
                        ->orLike('asset_maintenance.remarks', $keyword)
                    ->groupEnd();
        if ($bld   !== '') $q->where("COALESCE(b.name,  '—')", $bld);
        if ($unit  !== '') $q->where("COALESCE(ou.name, '—')", $unit);
        if ($month > 0)    $q->where('MONTH(asset_maintenance.maintenance_date)', $month);
        if ($year  > 0)    $q->where('YEAR(asset_maintenance.maintenance_date)',  $year);
        return $q->orderBy('asset_maintenance.maintenance_date', 'DESC')
                 ->paginate($perPage, 'default');
    }

    public function countByBuilding(string $bld = '', string $unit = '', int $month = 0, int $year = 0): int
    {
        $q = $this->join('asset_groups', 'asset_groups.group_id = asset_maintenance.group_id', 'left')
                  ->join('organizational_units ou', 'ou.unit_id = asset_groups.assigned_unit_id', 'left')
                  ->join('buildings b', 'b.building_id = ou.building_id', 'left');
        if ($bld   !== '') $q->where("COALESCE(b.name,  '—')", $bld);
        if ($unit  !== '') $q->where("COALESCE(ou.name, '—')", $unit);
        if ($month > 0)    $q->where('MONTH(asset_maintenance.maintenance_date)', $month);
        if ($year  > 0)    $q->where('YEAR(asset_maintenance.maintenance_date)',  $year);
        return (int) $q->countAllResults();
    }

    /**
     * Fetch a single maintenance record joined with its asset and group.
     */
    public function findWithAsset(int $id): ?array
    {
        $row = $this->select('asset_maintenance.*, assets.asset_tag, assets.brand_model, assets.serial_number, asset_groups.group_name')
                    ->join('assets', 'assets.asset_id = asset_maintenance.asset_id', 'left')
                    ->join('asset_groups', 'asset_groups.group_id = asset_maintenance.group_id', 'left')
                    ->where('asset_maintenance.maintenance_id', $id)
                    ->first();

        return $row ?: null;
    }
}
