<?php

namespace App\Models;

use CodeIgniter\Model;

class PreventiveMaintenancePlanModel extends Model
{
    protected $table            = 'pm_plans';
    protected $primaryKey       = 'plan_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'plan_year',
        'title',
        'department',
        'document_code',
        'prepared_by',
        'prepared_title',
        'reviewed_by',
        'reviewed_title',
        'approved_by',
        'approved_title',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Return paginated list of plans.
     */
    public function listPaginated(int $perPage = 10): array
    {
        return $this->orderBy('plan_year', 'DESC')->paginate($perPage, 'default');
    }

    /**
     * Fetch a plan with its items (and joined asset info).
     */
    public function findWithItems(int $planId): ?array
    {
        $plan = $this->find($planId);
        if (! $plan) {
            return null;
        }

        $db    = \Config\Database::connect();
        $items = $db->table('pm_plan_items')
                    ->select('pm_plan_items.*, assets.asset_tag, assets.brand_model')
                    ->join('assets', 'assets.asset_id = pm_plan_items.asset_id', 'left')
                    ->where('pm_plan_items.plan_id', $planId)
                    ->orderBy('pm_plan_items.sort_order', 'ASC')
                    ->orderBy('pm_plan_items.item_id', 'ASC')
                    ->get()
                    ->getResultArray();

        $plan['items'] = $items;
        return $plan;
    }
}
